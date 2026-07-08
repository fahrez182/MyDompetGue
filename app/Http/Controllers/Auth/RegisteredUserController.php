<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet; // Import the Wallet model
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // Define a list of supported currencies (should be consistent with other parts of the app)
    const SUPPORTED_CURRENCIES = ['USD', 'IDR', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SGD'];

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'base_currency' => ['required', 'string', 'size:3', 'in:' . implode(',', self::SUPPORTED_CURRENCIES)], // Added base_currency validation
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'base_currency' => $request->base_currency, // Added base_currency to user creation
            'password' => Hash::make($request->password),
        ]);

        // Create a default wallet for the new user
        $defaultWallet = Wallet::create([
            'user_id' => $user->id,
            'name' => 'Main Wallet', // Default name for the wallet
            'balance' => 0, // Initial balance
            'currency' => $user->base_currency, // Use the user's base currency
        ]);

        // Set the newly created wallet as the user's default wallet in the users table
        $user->default_wallet_id = $defaultWallet->id;
        $user->save();

        // Also set the is_default flag in the wallets table
        $defaultWallet->setDefault();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
