<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use App\Models\Rentman\CustomField;
use App\Models\Rentman\Account;
use App\Http\Requests\Rentman\StoreCustomFieldRequest;
use App\Http\Requests\Rentman\UpdateCustomFieldRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomFieldController extends Controller
{
    public function index(Request $request): View
    {
        $accounts = Account::all();

        $query = CustomField::with('rentmanAccount');

        // Filteren op account indien geselecteerd
        if ($request->has('account') && $request->account != '') {
            $query->where('account', $request->account);
        }

        $fields = $query
            ->orderBy('account')
            ->orderBy('name')
            ->paginate(20)
            ;

        return view('admin.rentman.customfield.index', compact('fields', 'accounts'));
    }

    public function create(): View
    {
        $accounts = Account::all();
        return view('admin.rentman.customfield.create', compact('accounts'));
    }

    public function store(StoreCustomFieldRequest $request): RedirectResponse
    {
        CustomField::create($request->validated());

        return redirect()->route('admin.rentman.customfield.index')
            ->with('success', __('Custom Field created successfully.'));
    }

    public function edit(CustomField $customField): View
    {
        $accounts = Account::all();
        return view('admin.rentman.customfield.edit', compact('customField', 'accounts'));
    }

    public function update(UpdateCustomFieldRequest $request, CustomField $customField): RedirectResponse
    {
        $customField->update($request->validated());

        return redirect()->route('admin.rentman.customfield.index')
            ->with('success', __('Custom Field updated successfully.'));
    }

    public function destroy(CustomField $customField): RedirectResponse
    {
        $customField->delete();
        return redirect()->route('admin.rentman.customfield.index')
            ->with('success', __('Custom Field deleted.'));
    }
}
