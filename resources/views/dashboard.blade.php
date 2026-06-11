@extends('layouts.app')
@section('page-title', 'Dashboard')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total Balance</p>
        <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-cyan-400' : 'text-red-400' }}">
            Rp {{ number_format($balance, 0, ',', '.') }}
        </p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total Income</p>
        <p class="text-2xl font-bold text-emerald-400">
            Rp {{ number_format($totalIncome, 0, ',', '.') }}
        </p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total Expense</p>
        <p class="text-2xl font-bold text-rose-400">
            Rp {{ number_format($totalExpense, 0, ',', '.') }}
        </p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4">

    {{-- Recent Transactions --}}
    <div class="col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-white">Recent Transactions</h3>
            <a href="{{ route('transactions.index') }}" class="text-xs text-violet-400 hover:underline">View all</a>
        </div>

        @forelse($recentTransactions as $tx)
        <div class="flex items-center justify-between py-3 
            border-b border-slate-200 dark:border-slate-800 
            last:border-0">
        @php
            $icon = match(strtolower($tx->category)) {
                'food'       => 'fa-solid fa-burger',
                'transport'  => 'fa-solid fa-car',
                'shopping'   => 'fa-solid fa-bag-shopping',
                'health'     => 'fa-solid fa-capsules',
                'salary'     => 'fa-solid fa-money-bill-wave',
                'bills'      => 'fa-solid fa-file-invoice',
                default      => 'fa-solid fa-credit-card'
            };
        @endphp

        <div class="flex items-center gap-3">

            <div class="
                w-9 h-9 rounded-full
                bg-slate-200 dark:bg-slate-800
                flex items-center justify-center
                text-base
                text-slate-700 dark:text-white
            ">
                <i class="{{ $icon }}"></i>
            </div>

            <div>

                <p class="
                    text-sm font-medium
                    text-slate-900 dark:text-white
                ">
                    {{ ucfirst($tx->category) }}
                </p>

                <p class="
                    text-xs
                    text-slate-500
                ">
                    {{ $tx->transaction_date->format('d M Y') }}
                </p>

            </div>

        </div>

        <span class="
            text-sm font-semibold
            {{ $tx->type === 'income'
                ? 'text-emerald-600 dark:text-emerald-400'
                : 'text-rose-600 dark:text-rose-400'
            }}
        ">

            {{ $tx->type === 'income' ? '+' : '-' }}
            Rp {{ number_format($tx->amount, 0, ',', '.') }}

        </span>

    </div>
        @empty
        <p class="text-sm text-slate-500 py-4 text-center">Belum ada transaksi.</p>
        @endforelse
    </div>

    {{-- AI Financial Assistant --}}
    <div class=" bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 flex flex-col">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-lg">🤖</span>
            <h3 class="font-semibold text-white">AI Financial Assistant</h3>
        </div>

        <div id="ai-result"
             class="flex-1 min-h-32 text-sm text-slate-400 leading-relaxed mb-4 overflow-y-auto">
            Klik tombol di bawah untuk mendapatkan analisis keuangan dari AI.
        </div>

        <button id="ai-btn" onclick="runAIAnalysis()"
                class="w-full py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm
                       font-medium rounded-lg transition flex items-center justify-center gap-2">
            <span id="ai-btn-icon">✨</span>
            <span id="ai-btn-text">Analyze My Spending</span>
        </button>
    </div>

</div>

<script>
function runAIAnalysis() {
    const result  = document.getElementById('ai-result');
    const btnText = document.getElementById('ai-btn-text');
    const btnIcon = document.getElementById('ai-btn-icon');

    result.innerHTML  = '<span class="text-violet-400 animate-pulse">AI sedang menganalisis...</span>';
    btnText.textContent = 'Analyzing...';
    btnIcon.textContent = '⏳';

    const source = new EventSource('{{ route("ai.analyze") }}');

    source.onmessage = function(e) {
        if (e.data === '[DONE]') {
            source.close();
            btnText.textContent = 'Analyze My Spending';
            btnIcon.textContent = '✨';
            return;
        }

        if (result.querySelector('.animate-pulse')) {
            result.innerHTML = '';
        }

        result.innerHTML += e.data;
    };

    source.onerror = function() {
        source.close();
        result.innerHTML += '<br><span class="text-red-400">Gagal menghubungi AI.</span>';
        btnText.textContent = 'Analyze My Spending';
        btnIcon.textContent = '✨';
    };
}
</script>
@endsection