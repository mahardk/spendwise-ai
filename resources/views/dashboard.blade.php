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

{{-- Charts --}}
<div class="grid grid-cols-2 gap-4 mb-6">

    {{-- Area: Income vs Expense per bulan --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5">
        <h3 class="font-semibold text-white mb-1">Income vs Expense</h3>
        <p class="text-xs text-slate-500 mb-4">Monthly trend</p>
        <div style="position:relative; width:100%; height:220px;">
            <canvas id="areaChart" role="img" aria-label="Area chart showing monthly income vs expense trends"></canvas>
        </div>
    </div>

    {{-- Doughnut: Expense by Category --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5">
        <h3 class="font-semibold text-white mb-1">Expense by Category</h3>
        <p class="text-xs text-slate-500 mb-4">Breakdown</p>
        <div id="donut-legend" class="flex flex-wrap gap-x-4 gap-y-1 mb-3"></div>
        <div style="position:relative; width:100%; height:180px;">
            <canvas id="donutChart" role="img" aria-label="Doughnut chart showing expense breakdown by category"></canvas>
        </div>
    </div>

</div>

<script>
(function() {
    const monthlyRaw  = @json($monthly);
    const months      = Object.keys(monthlyRaw);
    const incomeData  = months.map(m => {
        const row = (monthlyRaw[m] || []).find(r => r.type === 'income');
        return row ? parseFloat(row.total) : 0;
    });
    const expenseData = months.map(m => {
        const row = (monthlyRaw[m] || []).find(r => r.type === 'expense');
        return row ? parseFloat(row.total) : 0;
    });

    const pieLabels = @json($expenseByCategory->pluck('category'));
    const pieData   = @json($expenseByCategory->pluck('total')->map(fn($v) => (float)$v));

    function makeGradient(ctx, colorTop, colorBottom) {
        const g = ctx.createLinearGradient(0, 0, 0, 220);
        g.addColorStop(0, colorTop);
        g.addColorStop(1, colorBottom);
        return g;
    }

    // ── Area Chart ──────────────────────────────────────────────
    const aCtx = document.getElementById('areaChart').getContext('2d');
    const incomeGrad  = makeGradient(aCtx, 'rgba(16,185,129,0.35)', 'rgba(16,185,129,0)');
    const expenseGrad = makeGradient(aCtx, 'rgba(244,63,94,0.35)',  'rgba(244,63,94,0)');

    new Chart(aCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Income',
                    data: incomeData,
                    borderColor: '#10b981',
                    borderWidth: 2.5,
                    backgroundColor: incomeGrad,
                    fill: true,
                    tension: 0.45,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Expense',
                    data: expenseData,
                    borderColor: '#f43f5e',
                    borderWidth: 2.5,
                    backgroundColor: expenseGrad,
                    fill: true,
                    tension: 0.45,
                    pointRadius: 4,
                    pointBackgroundColor: '#f43f5e',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    borderDash: [0],
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#94a3b8',
                    bodyColor: '#f1f5f9',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: Rp ${ctx.parsed.y.toLocaleString('id-ID')}`
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#64748b', font: { size: 11 }, autoSkip: false, maxRotation: 0 },
                    grid: { color: 'rgba(51,65,85,0.5)' },
                    border: { display: false }
                },
                y: {
                    ticks: {
                        color: '#64748b',
                        font: { size: 11 },
                        callback: v => 'Rp ' + (v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : (v/1000)+'rb')
                    },
                    grid: { color: 'rgba(51,65,85,0.5)' },
                    border: { display: false }
                }
            }
        }
    });

    // ── Doughnut Chart ──────────────────────────────────────────
    const palette = ['#8b5cf6','#06b6d4','#f43f5e','#10b981','#f59e0b','#3b82f6','#ec4899','#84cc16'];

    const legendEl = document.getElementById('donut-legend');
    pieLabels.forEach((lbl, i) => {
        const pct = Math.round(pieData[i] / pieData.reduce((a,b) => a+b, 0) * 100);
        legendEl.innerHTML += `
            <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#94a3b8;">
                <span style="width:8px;height:8px;border-radius:2px;background:${palette[i % palette.length]};flex-shrink:0;"></span>
                ${lbl} ${pct}%
            </span>`;
    });

    new Chart(document.getElementById('donutChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: palette,
                borderWidth: 3,
                borderColor: '#0f172a',
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#94a3b8',
                    bodyColor: '#f1f5f9',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: ctx => ` Rp ${ctx.parsed.toLocaleString('id-ID')}`
                    }
                }
            }
        }
    });
})();
</script>

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