<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rentman\StoreAccountRequest;
use App\Http\Requests\Rentman\UpdateAccountRequest;
use App\Models\Rentman\Account;
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
    public function store(StoreAccountRequest $request): RedirectResponse
    {
        // De data is hier al gevalideerd
        Account::create($request->validated());

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
    public function update(UpdateAccountRequest $request, Account $account): RedirectResponse
    {
        $account->update($request->validated());

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