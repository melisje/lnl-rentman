<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Account;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    /**
     * Display a listing of the accounts.
     */
    public function index(): View
    {
        $accounts = Account::paginate(15);
        return view('admin.rentman.account.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create(): View
    {
        return view('admin.rentman.account.create');
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account'       => 'required|string|unique:rm_accounts,account|max:255',
            'api_token'     => 'required|string',
            'webhook_token' => 'nullable|string',
            'url'           => 'required|url',
        ]);

        Account::create($validated);

        return redirect()->route('admin.rentman.accounts.index')
            ->with('success', __('Account created successfully.'));
    }

    /**
     * Display the specified account.
     * This was the missing method!
     */
    public function show(Account $account): View
    {
        return view('admin.rentman.account.show', compact('account'));
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(Account $account): View
    {
        return view('admin.rentman.account.edit', compact('account'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, Account $account): RedirectResponse
    {
        $validated = $request->validate([
            'api_token'     => 'required|string',
            'webhook_token' => 'nullable|string',
            'url'           => 'required|url',
        ]);

        $account->update($validated);

        return redirect()->route('admin.rentman.accounts.index')
            ->with('success', __('Account updated successfully.'));
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(Account $account): RedirectResponse
    {
        $account->delete();

        return redirect()->route('admin.rentman.accounts.index')
            ->with('success', __('Account deleted successfully.'));
    }
}