<?php

namespace App\Helpers;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Jobs\FetchExchangeRates; // Import the job

class ExchangeRateHelper
{
    /**
     * Get the exchange rate between two currencies for a specific date.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param string|null $date (Y-m-d format, defaults to today)
     * @return float|null
     */
    public static function getRate(string $fromCurrency, string $toCurrency, ?string $date = null): ?float
    {
        $date = $date ?? now()->toDateString();
        $cacheKey = "exchange_rate_{$fromCurrency}_{$toCurrency}_{$date}";
        $commonBaseCurrency = 'USD'; // The common base currency fetched by FetchExchangeRates job

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($fromCurrency, $toCurrency, $date, $commonBaseCurrency) {
            // 1. Try to find the direct rate
            $rate = ExchangeRate::where('from_currency', $fromCurrency)
                                ->where('to_currency', $toCurrency)
                                ->where('date', $date)
                                ->first();

            if ($rate) {
                Log::debug('ExchangeRateHelper: Direct rate found', [
                    'from' => $fromCurrency,
                    'to' => $toCurrency,
                    'date' => $date,
                    'rate' => $rate->rate,
                ]);
                return (float) $rate->rate;
            }

            // 2. If direct rate not found, try to find the inverse rate
            $inverseRate = ExchangeRate::where('from_currency', $toCurrency)
                                       ->where('to_currency', $fromCurrency)
                                       ->where('date', $date)
                                       ->first();

            if ($inverseRate && $inverseRate->rate != 0) {
                $calculatedRate = 1 / $inverseRate->rate;
                Log::debug('ExchangeRateHelper: Inverse rate found and calculated', [
                    'from' => $fromCurrency,
                    'to' => $toCurrency,
                    'date' => $date,
                    'inverse_rate_found' => $inverseRate->rate,
                    'calculated_rate' => $calculatedRate,
                ]);
                return (float) $calculatedRate;
            }

            // 3. If direct and inverse rates not found, try triangulation via common base currency (USD)
            if ($fromCurrency !== $commonBaseCurrency && $toCurrency !== $commonBaseCurrency) {
                Log::debug('ExchangeRateHelper: Attempting triangulation via ' . $commonBaseCurrency, [
                    'from' => $fromCurrency,
                    'to' => $toCurrency,
                    'date' => $date,
                ]);

                // Temporarily disable cache for intermediate getRate calls to avoid infinite loops
                // and ensure fresh data if the sub-call dispatches a job
                $rateFromToCommon = self::getRate($fromCurrency, $commonBaseCurrency, $date); // e.g., EUR to USD
                $rateCommonToTo = self::getRate($commonBaseCurrency, $toCurrency, $date);     // e.g., USD to IDR

                Log::debug('ExchangeRateHelper: Triangulation intermediate rates', [
                    'rate_from_to_common' => $rateFromToCommon, // EUR to USD
                    'rate_common_to_to' => $rateCommonToTo,     // USD to IDR
                ]);

                if ($rateFromToCommon !== null && $rateCommonToTo !== null) {
                    $triangulatedRate = $rateFromToCommon * $rateCommonToTo;
                    Log::debug('ExchangeRateHelper: Triangulated rate found and calculated via ' . $commonBaseCurrency, [
                        'from' => $fromCurrency,
                        'to' => $toCurrency,
                        'date' => $date,
                        'rate_from_to_common' => $rateFromToCommon,
                        'rate_common_to_to' => $rateCommonToTo,
                        'triangulated_rate' => $triangulatedRate,
                    ]);
                    return (float) $triangulatedRate;
                }
            }

            // 4. If no rate found (direct, inverse, or triangulated), dispatch a job to fetch it
            Log::warning("ExchangeRateHelper: Exchange rate not found (direct, inverse, or triangulated) for {$fromCurrency} to {$toCurrency} on {$date}. Dispatching FetchExchangeRates job for {$date}.");
            FetchExchangeRates::dispatch($date); // Dispatch the job for the missing date

            return null; // Still return null for the current request as the job is asynchronous
        });
    }

    /**
     * Convert an amount from one currency to another.
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param string|null $date (Y-m-d format, defaults to today)
     * @return float|null
     */
    public static function convert(float $amount, string $fromCurrency, string $toCurrency, ?string $date = null): ?float
    {
        Log::debug('ExchangeRateHelper: convert called', [
            'amount' => $amount,
            'from_currency' => $fromCurrency,
            'to_currency' => $toCurrency,
            'date' => $date,
        ]);

        if ($fromCurrency === $toCurrency) {
            Log::debug('ExchangeRateHelper: Currencies are the same, returning original amount.');
            return $amount;
        }

        $rate = self::getRate($fromCurrency, $toCurrency, $date);

        if ($rate !== null) {
            $convertedAmount = $amount * $rate;
            Log::debug('ExchangeRateHelper: Conversion successful', [
                'rate' => $rate,
                'converted_amount' => $convertedAmount,
            ]);
            return $convertedAmount;
        }

        Log::warning("ExchangeRateHelper: Conversion failed due to missing rate for {$fromCurrency} to {$toCurrency} on {$date}.");
        return null;
    }
}
