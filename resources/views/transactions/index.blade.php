@extends('layouts.app')
@section('page-title', 'Transactions')

@section('content')

<div class="flex justify-end mb-4">
    <a href="{{ route('transactions.create') }}"
       class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">
        + Add Transaction
    </a>
</div>

<div class=" bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="text-left px-5 py-3">Date</th>
                    <th class="text-left px-5 py-3">Category</th>
                    <th class="text-left px-5 py-3">Type</th>
                    <th class="text-left px-5 py-3">Description</th>
                    <th class="text-right px-5 py-3">Amount</th>
                    <th class="text-center px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr class="border-b border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition">
                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $tx->transaction_date->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400 font-medium">{{ $tx->category }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $tx->type === 'income' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                            {{ ucfirst($tx->type) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $tx->description ?? '-' }}</td>
                    <td class="px-5 py-4 text-right font-semibold
                        {{ $tx->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('transactions.edit', $tx) }}"
                            class="px-3 py-1 bg-slate-700 hover:bg-slate-600 text-white text-xs rounded-lg transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('transactions.destroy', $tx) }}"
                                onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1 bg-rose-500/20 hover:bg-rose-500/40 text-rose-400 text-xs rounded-lg transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    

    <div class="px-5 py-3">
        {{ $transactions->links() }}
    </div>
</div>
@endsection