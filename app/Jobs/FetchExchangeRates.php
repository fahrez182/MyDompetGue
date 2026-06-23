<?php

namespace App\Jobs;

use App\Models\ExchangeRate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchExchangeRates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $date;

    /**
     * Create a new job instance.
     *
     * @param string|null $date The date for which to fetch exchange rates (Y-m-d format). Defaults to today.
     */
    public function __construct(?string $date = null)
    {
        $this->date = $date ?? now()->toDateString();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $apiKey = config('services.exchange_rate_api.key');
        if (!$apiKey) {
            Log::error('FetchExchangeRates: EXCHANGE_RATE_API_KEY is not set in .env or services config.');
            return;
        }

        $baseCurrency = 'USD'; // You can make this configurable or dynamic
        $targetCurrencies = ['IDR', 'EUR', 'GBP', 'JPY']; // Add more as needed
        $fetchDate = $this->date; // Use the date passed to the constructor

        Log::debug("FetchExchangeRates: Attempting to fetch rates for date {$fetchDate}.");

        // Check if rates for the specific date already exist in the database for all target currencies
        $existingRatesCount = ExchangeRate::where('from_currency', $baseCurrency)
            ->whereIn('to_currency', $targetCurrencies)
            ->where('date', $fetchDate)
            ->count();

        if ($existingRatesCount === count($targetCurrencies)) {
            Log::info("FetchExchangeRates: Exchange rates for {$fetchDate} are already up-to-date in the database. Skipping API call.");
            return;
        }

        // ExchangeRate-API free tier only supports 'latest' rates, not historical for specific dates.
        // If historical data is needed for past dates, a paid plan or a different API might be required.
        // For now, we will fetch 'latest' and store it with the requested date.
        // This assumes 'latest' rates are acceptable for past dates if not already present.
        $url = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$baseCurrency}";

        Log::debug("FetchExchangeRates: Making API request to {$url}");

        try {
            $response = Http::get($url);
            $data = $response->json();

            if ($response->successful()) {
                if (isset($data['conversion_rates'])) {
                    Log::debug("FetchExchangeRates: API response successful, processing conversion rates.", ['base_code' => $data['base_code'], 'conversion_rates_keys' => array_keys($data['conversion_rates'])]);

                    foreach ($targetCurrencies as $target) {
                        if (isset($data['conversion_rates'][$target])) {
                            $rate = $data['conversion_rates'][$target];
                            Log::debug("FetchExchangeRates: Storing rate for {$baseCurrency} to {$target} on {$fetchDate}.", ['rate' => $rate]);
                            ExchangeRate::updateOrCreate(
                                [
                                    'from_currency' => $baseCurrency,
                                    'to_currency' => $target,
                                    'date' => $fetchDate, // Store with the requested date
                                ],
                                [
                                    'rate' => $rate,
                                ]
                            );
                        } else {
                            Log::warning("FetchExchangeRates: Exchange rate for {$baseCurrency} to {$target} not found in API response for {$fetchDate}.");
                        }
                    }
                    Log::info("FetchExchangeRates: Exchange rates fetched and stored successfully for {$fetchDate}.");
                } else {
                    Log::error("FetchExchangeRates: API response successful but 'conversion_rates' key is missing.", ['response' => $data]);
                }
            } else {
                Log::error('FetchExchangeRates: Failed to fetch exchange rates from API.', ['status' => $response->status(), 'response' => $data]);
            }
        } catch (\Exception $e) {
            Log::error('FetchExchangeRates: Error fetching exchange rates: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
}
