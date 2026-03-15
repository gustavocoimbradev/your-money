<?php

namespace App\Http\Controllers;

use App\Actions\Accounts\CreateAccountAction;
use App\Actions\Accounts\UpdateAccountAction;
use App\Dto\Accounts\CreateAccountDto;
use App\Dto\Accounts\UpdateAccountDto;
use App\Http\Requests\Accounts\StoreAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AccountController extends Controller
{


    public function index() 
    {
        $accounts = auth()->user()->accounts()->latest()->get();
        $archived_accounts = auth()->user()->accounts()->archived()->latest()->get();
        return Inertia::render('accounts/Index', compact('accounts', 'archived_accounts'));
    }

    public function create()
    {
        return Inertia::render('accounts/Create');
    }

    public function store(StoreAccountRequest $request, CreateAccountAction $action)
    { 
        $dto = CreateAccountDto::fromRequest($request);
        if ($action($dto)) {
            return to_route('accounts.index')->with('success', 'Account created successfully!');
        }
        return back()->withErrors('error', 'Failed to create account');
    }

    public function show(Account $account)
    {
        Gate::authorize('view', $account);
        $single_account = auth()->user()->accounts()->count() === 1;
        return Inertia::render('accounts/Show', compact('account', 'single_account'));
    }

    public function edit(Account $account)
    {
        Gate::authorize('update', $account);
        return Inertia::render('accounts/Edit', compact('account'));
    }

    public function update(UpdateAccountRequest $request, Account $account, UpdateAccountAction $action)
    {
        Gate::authorize('update', $account);
        $dto = UpdateAccountDto::fromRequest($request);
        if ($action($dto, $account)) {
            return to_route('accounts.show', $account)->with('success', 'Account successfully changed!');
        }
        return back()->withErrors('error', 'Failed to change account');
    }

    public function destroy(Account $account)
    {
        Gate::authorize('delete', $account);
        if ($account->delete()) {
            return to_route('accounts.index')->with('success', 'Account successfully archived!');
        }
        return back()->withErrors('error', 'Failed to archive account');
    }
    public function restore(Account $account) 
    {
        Gate::authorize('restore', $account);
        if ($account->restore()) {
            return to_route('accounts.index')->with('success', 'Account successfully archived!');
        }
        return back()->withErrors('error', 'Failed to restore account');
    }
}
