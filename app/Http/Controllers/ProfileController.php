<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Helpers\ExchangeRateHelper; // Import the helper
use Carbon\Carbon; // Import Carbon

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $userBaseCurrency = $user->base_currency ?? 'USD'; // Get user's base currency

        $wallets = $user->wallets()->latest()->get(); // Fetch user's wallets

        // Convert each wallet's balance to the user's base currency
        $wallets->map(function ($wallet) use ($userBaseCurrency) {
            $wallet->converted_balance = ExchangeRateHelper::convert(
                $wallet->balance,
                $wallet->currency,
                $userBaseCurrency,
                Carbon::now()->toDateString() // Use current date for conversion
            ) ?? $wallet->balance; // Fallback to original balance if conversion fails
            return $wallet;
        });

        return view('profile.edit', [
            'user' => $user,
            'wallets' => $wallets, // Pass wallets to the view
            'userBaseCurrency' => $userBaseCurrency, // Pass userBaseCurrency to the view
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
