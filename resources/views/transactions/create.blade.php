@extends('layouts.app')
@section('page-title', 'Add Transaction')

@section('content')
<div class="max-w-lg mx-auto">
<div class=" bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6">
    <h3 class="font-semibold text-white mb-6">New Transaction</h3>

    <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
        @csrf

        {{-- Type --}}
        <div>
            <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Type</label>
            <div class="flex gap-3">
                <label class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg border cursor-pointer
                              border-emerald-500/50 bg-emerald-500/10 text-emerald-400 text-sm font-medium has-[:checked]:bg-emerald-500 has-[:checked]:text-white transition">
                    <input type="radio" name="type" value="income" class="hidden"
                           {{ old('type') === 'income' ? 'checked' : '' }}>
                    💰 Income
                </label>
                <label class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-lg border cursor-pointer
                              border-rose-500/50 bg-rose-500/10 text-rose-400 text-sm font-medium has-[:checked]:bg-rose-500 has-[:checked]:text-white transition">
                    <input type="radio" name="type" value="expense" class="hidden"
                           {{ old('type', 'expense') === 'expense' ? 'checked' : '' }}>
                    💸 Expense
                </label>
            </div>
            @error('type')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Category --}}
        <div>
            <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Category</label>
            <select name="category"
                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500">
                @foreach(['Food','Transport','Shopping','Health','Bills','Entertainment','Salary','Other'] as $cat)
                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Amount --}}
        <div>
            <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Amount</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                <input type="number" name="amount" value="{{ old('amount') }}" min="0" step="100"
                       class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-800 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-violet-500"
                       placeholder="0">
            </div>
            @error('amount')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Description (optional)</label>
            <input type="text" name="description" value="{{ old('description') }}"
                   class="w-full  bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500"
                   placeholder="e.g. Lunch at warung">
        </div>

        {{-- Date --}}
        <div>
            <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Date</label>
            <input type="date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                   class="w-full  bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500">
            @error('transaction_date')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('transactions.index') }}"
               class="flex-1 py-2.5 text-center border border-slate-700 text-slate-400 text-sm rounded-lg hover:bg-slate-800 transition">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">
                Save Transaction
            </button>
        </div>
    </form>
</div>
</div>
@endsection