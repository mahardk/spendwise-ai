@extends('layouts.app')
@section('page-title', 'Goals')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Form Buat Goal Baru --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5">
        <h3 class="font-semibold text-slate-900 dark:text-white mb-1">New Goal</h3>
        <p class="text-xs text-slate-500 mb-4">Set a savings target to reach</p>

        <form method="POST" action="{{ route('goals.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs text-slate-500 uppercase tracking-wider block mb-1.5">Goal Name</label>
                <input type="text" name="name" placeholder="e.g. Beli Laptop"
                       value="{{ old('name') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
                @error('name')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-xs text-slate-500 uppercase tracking-wider block mb-1.5">Icon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', '🎯') }}" maxlength="4"
                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
            </div>

            <div>
                <label class="text-xs text-slate-500 uppercase tracking-wider block mb-1.5">Target Amount</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                    <input type="number" name="target_amount" min="1000" step="1000"
                           placeholder="0" value="{{ old('target_amount') }}"
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
                </div>
                @error('target_amount')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-xs text-slate-500 uppercase tracking-wider block mb-1.5">Deadline <span class="normal-case text-slate-400">(optional)</span></label>
                <input type="date" name="deadline" value="{{ old('deadline') }}"
                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
                @error('deadline')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">
                Create Goal
            </button>
        </form>
    </div>

    {{-- Daftar Goals --}}
    <div class="lg:col-span-2 space-y-4">
        @forelse($goals as $goal)
        @php
            $remaining = max(0, $goal->target_amount - $goal->saved_amount);
            $isComplete = $goal->progress_percent >= 100;
        @endphp

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5">

            {{-- Header --}}
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xl shrink-0">
                        {{ $goal->icon }}
                    </div>
                    <div>
                        <h4 class="font-semibold text-slate-900 dark:text-white leading-tight">{{ $goal->name }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">
                            @if($goal->deadline)
                                Deadline {{ $goal->deadline->format('d M Y') }}
                                @php $daysLeft = (int)now()->diffInDays($goal->deadline->startOfDay(), false) @endphp
                                @if($daysLeft > 0)
                                    · <span class="text-amber-400">{{ $daysLeft }} hari lagi</span>
                                @elseif(!$isComplete)
                                    · <span class="text-rose-400">Sudah lewat</span>
                                @endif
                            @else
                                No deadline
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($isComplete)
                    <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-medium rounded-full">
                        🎉 Tercapai
                    </span>
                    @endif
                    <form method="POST" action="{{ route('goals.destroy', $goal) }}"
                          onsubmit="return confirm('Hapus goal ini?')">
                        @csrf @method('DELETE')
                        <button class="text-slate-400 hover:text-rose-400 transition p-1" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                    <p class="text-xs text-slate-500 mb-0.5">Saved</p>
                    <p class="text-sm font-semibold text-emerald-400">Rp {{ number_format($goal->saved_amount, 0, ',', '.') }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                    <p class="text-xs text-slate-500 mb-0.5">Target</p>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-3">
                    <p class="text-xs text-slate-500 mb-0.5">Remaining</p>
                    <p class="text-sm font-semibold {{ $isComplete ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ $isComplete ? '✓ Done' : 'Rp ' . number_format($remaining, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-xs text-slate-500">Progress</span>
                    <span class="text-xs font-semibold {{ $isComplete ? 'text-emerald-400' : 'text-violet-400' }}">
                        {{ $goal->progress_percent }}%
                    </span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-700
                        @if($isComplete) bg-emerald-400
                        @elseif($goal->progress_percent >= 75) bg-cyan-400
                        @elseif($goal->progress_percent >= 40) bg-violet-500
                        @else bg-rose-400
                        @endif"
                         style="width: {{ $goal->progress_percent }}%">
                    </div>
                </div>
            </div>

            {{-- Add Saving Form --}}
            @if(!$isComplete)
            <form method="POST" action="{{ route('goals.saving', $goal) }}" class="flex gap-2">
                @csrf
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">Rp</span>
                    <input type="number" name="amount" min="1000" step="1000"
                           placeholder="Tambah tabungan"
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg pl-9 pr-4 py-2 text-sm focus:outline-none focus:border-violet-500 transition">
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">
                    + Tabung
                </button>
            </form>
            @endif

        </div>
        @empty
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-12 text-center">
            <p class="text-3xl mb-3">🎯</p>
            <p class="text-slate-900 dark:text-white font-medium mb-1">Belum ada goals</p>
            <p class="text-sm text-slate-500">Buat goal pertamamu di form sebelah kiri.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection