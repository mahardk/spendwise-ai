<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: true, sidebarOpen: false }"
      class="dark" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SpendWise AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- OVERLAY mobile --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden"
         style="display: none;"></div>

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed lg:static inset-y-0 left-0 z-30
                  w-64 bg-white dark:bg-slate-900
                  border-r border-slate-200 dark:border-slate-800
                  flex flex-col shrink-0
                  transform transition-transform duration-200 ease-in-out
                  lg:translate-x-0">

        {{-- Logo --}}
        <div class="px-6 py-6 border-b border-slate-200 dark:border-slate-800">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-violet-400 to-cyan-400 bg-clip-text text-transparent">
                SpendWise
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Smart Personal Expense Tracker</p>
            <p class="text-xs text-slate-500 mt-0.5">with AI Financial Assistant</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->routeIs('dashboard') ? 'bg-violet-600 text-white' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}
                      transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('transactions.index') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->routeIs('transactions.*') ? 'bg-violet-600 text-white' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}
                      transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Transactions
            </a>

            <a href="{{ route('transactions.create') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Transaction
            </a>

            <a href="{{ route('goals.index') }}" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->routeIs('goals.*') ? 'bg-violet-600 text-white' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}
                      transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Goals
            </a>
        </nav>

        {{-- User & Logout --}}
        <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-800 space-y-2">
            <div class="px-3 py-2">
                <p class="text-xs text-slate-500">Logged in as</p>
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm
                               text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 overflow-y-auto bg-slate-100 dark:bg-slate-950 min-w-0">

        {{-- Topbar --}}
        <header class="sticky top-0 z-10 bg-white/80 dark:bg-slate-950/80 backdrop-blur
                       border-b border-slate-200 dark:border-slate-800
                       px-4 sm:px-8 py-4
                       flex items-center justify-between gap-4">

            {{-- Hamburger (mobile) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 -ml-1 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                    aria-label="Toggle menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h2 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white flex-1 text-center lg:text-left">
                @yield('page-title', 'Dashboard')
            </h2>

            <div class="flex items-center gap-2 sm:gap-3">
                <span class="hidden sm:block text-sm text-slate-400">{{ now()->format('d M Y') }}</span>
                <button onclick="toggleTheme()"
                        class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                        id="theme-btn">
                    <i id="theme-icon" class="fa-solid fa-moon text-sm"></i>
                </button>
            </div>
        </header>

        <div class="px-4 sm:px-6 lg:px-8 py-6">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-600 dark:text-emerald-400 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</div>

</body>
</html>