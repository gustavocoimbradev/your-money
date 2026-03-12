<?php

namespace App\Http\Controllers;

use App\Actions\Transactions\CreateTransactionAction;
use App\Actions\Transactions\UpdateTransactionAction;
use App\Dto\Transactions\{CreateTransactionDto, UpdateTransactionDto};
use App\Http\Requests\Transactions\{StoreTransactionRequest, UpdateTransactionRequest};
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = auth()->user()->transactions()->with('account')->latestFirst()->get();
        $archived_transactions = auth()->user()->transactions()->with('account')->archived()->latestFirst()->get();  
        $current_balance = auth()->user()->accounts()->sum('current_balance');
        return Inertia::render('transactions/Index', compact('transactions', 'archived_transactions', 'current_balance'));
    }

    public function create()
    { 
        $accounts = auth()->user()->accounts;
        return Inertia::render('transactions/Create', compact('accounts'));
    }

    public function store(StoreTransactionRequest $request, CreateTransactionAction $action)
    {   
        $dto = CreateTransactionDto::fromRequest($request);
        if ($action($dto)) {
            return to_route('transactions.index')->with('success', 'Transaction created successfully!');
        }
        return back()->withErrors('error', 'Failed to create transaction');
    }

    public function show(Transaction $transaction)
    {   
        Gate::authorize('view', $transaction);
        return Inertia::render('transactions/Show');
    }

    public function edit(Transaction $transaction)
    {
        Gate::authorize('update', $transaction);
        $accounts = auth()->user()->accounts;
        return Inertia::render('transactions/Edit', compact('transaction', 'accounts'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction, UpdateTransactionAction $action, Account $account)
    {
        Gate::authorize('update', $transaction);
        $dto = UpdateTransactionDto::fromRequest($request);
        if ($action($dto, $transaction)) {
            return to_route('transactions.index')->with('success', 'Transaction successfully changed!');
        }
        return back()->withErrors('error', 'Failed to change transaction');
    }

    public function destroy(Transaction $transaction)
    {   
        Gate::authorize('delete', $transaction);
        if ($transaction->delete()) {
            return to_route('transactions.index')->with('success', 'Transaction successfully archived!');
        }
        return back()->withErrors('error', 'Failed to archive transaction');
    }

    public function forceDestroy(Transaction $transaction)
    {   
        Gate::authorize('delete', $transaction);
        if ($transaction->forceDelete()) {
            return to_route('transactions.index')->with('success', 'Transaction successfully deleted!');
        }
        return back()->withErrors('error', 'Failed to delete transaction');
    }

     public function restore(Transaction $transaction) 
    {
        Gate::authorize('restore', $transaction);
        if ($transaction->restore()) {
            return to_route('transactions.index')->with('success', 'Transaction restored successfully!');
        }
        return back()->withErrors('error', 'Failed to restore transaction');
    }

    public function togglePaid(Transaction $transaction)
    {
        Gate::authorize('update', $transaction);
        $transaction->update(['paid' => !$transaction->paid]);
    }
}
