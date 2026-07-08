<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = Auth::user()->wallets()->latest()->get();
        return view('wallets.index', compact('wallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wallets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('wallets')->where(function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })],
            'balance' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
        ]);

        $wallet = $user->wallets()->create($validated);

        // If this is the user's first wallet, make it the default
        if ($user->wallets()->count() === 1) {
            $wallet->setDefault();
        }

        return redirect()->route('profile.edit')->with('success', 'Wallet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        $this->authorize('view', $wallet);
        return view('wallets.show', compact('wallet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        $this->authorize('update', $wallet);
        return view('wallets.edit', compact('wallet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('wallets')->where(function ($query) use ($user, $wallet) {
                return $query->where('user_id', $user->id)->where('id', '!=', $wallet->id);
            })],
            'balance' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
        ]);

        $wallet->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Wallet updated successfully.');
    }

    /**
     * Set the specified wallet as the default for the authenticated user.
     */
    public function setDefault(Wallet $wallet)
    {
        // Ensure the user relationship is loaded before calling setDefault
        $wallet->load('user');

        $this->authorize('update', $wallet); // Use update policy for setting default

        $wallet->setDefault();

        // Redirect to dashboard after setting default from quick change
        return redirect()->route('dashboard')->with('success', 'Default wallet set successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        $this->authorize('delete', $wallet);

        $user = Auth::user();

        // Prevent deleting the last wallet
        if ($user->wallets()->count() <= 1) {
            return redirect()->route('profile.edit')->with('error', 'You must have at least one wallet.');
        }

        // If the wallet being deleted is the default, set another one as default
        if ($wallet->is_default) {
            $newDefaultWallet = $user->wallets()->where('id', '!=', $wallet->id)->first();
            if ($newDefaultWallet) {
                $newDefaultWallet->setDefault();
            }
        }

        $wallet->delete();

        return redirect()->route('profile.edit')->with('success', 'Wallet deleted successfully.');
    }
}
