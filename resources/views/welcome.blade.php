<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SpendWise - Smart Personal Expense Tracker</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        .bg-grid {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%2394a3b8' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="antialiased bg-slate-950 text-slate-200 min-h-screen relative overflow-x-hidden">
    <div class="bg-grid absolute inset-0 z-0"></div>
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-violet-600/20 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-cyan-600/20 blur-[120px] rounded-full"></div>

    <div class="relative z-10">
        <nav class="flex items-center justify-between px-6 py-8 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                    <i class="fa-solid fa-wallet text-white text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-violet-400 to-cyan-400 bg-clip-text text-transparent">
                    SpendWise
                </h1>
            </div>

            @if (Route::has('login'))
                <div class="flex gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl text-sm font-medium transition">
                            Open Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-medium hover:text-white transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-xl shadow-lg shadow-violet-600/20 transition">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>

        <main class="max-w-7xl mx-auto px-6 pt-20 pb-32 grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-400 text-xs font-medium mb-6">
                    <span class="flex h-2 w-2 rounded-full bg-violet-500 animate-pulse"></span>
                    Now with AI Financial Insights
                </div>
                <h2 class="text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    Smart way to track <br>
                    <span class="bg-gradient-to-r from-violet-400 to-cyan-400 bg-clip-text text-transparent">your expenses.</span>
                </h2>
                <p class="text-lg text-slate-400 mb-10 max-w-md leading-relaxed">
                    SpendWise membantu Anda mengelola keuangan dengan bantuan AI Assistant. Pantau saldo, pemasukan, dan pengeluaran dalam satu dashboard modern.
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-2xl shadow-xl shadow-violet-600/25 transition flex items-center gap-2">
                        Get Started! <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                </div>
            </div>

            <div class="relative">
                <div class="bg-slate-900/50 border border-slate-800 p-4 rounded-[2rem] backdrop-blur-sm shadow-2xl">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4">
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">Balance</p>
                            <p class="text-lg font-bold text-cyan-400">Rp 12.500.000</p>
                        </div>
                        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4">
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">Expense</p>
                            <p class="text-lg font-bold text-rose-400">Rp 3.250.000</p>
                        </div>
                    </div>
                    
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <span>✨</span>
                            <p class="text-xs font-semibold text-white">AI Assistant</p>
                        </div>
                        <div class="space-y-2">
                            <div class="h-2 w-full bg-slate-800 rounded-full"></div>
                            <div class="h-2 w-4/5 bg-slate-800 rounded-full"></div>
                            <div class="h-2 w-3/4 bg-slate-800 rounded-full"></div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-900 flex justify-end">
                            <div class="px-4 py-2 bg-violet-600 rounded-lg text-[10px] font-bold text-white uppercase tracking-tighter">
                                Analysis Ready
                            </div>
                        </div>
                    </div>
                </div>

                <div class="absolute -top-6 -right-6 w-16 h-16 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center backdrop-blur-md animate-bounce">
                    <i class="fa-solid fa-chart-line text-emerald-400 text-2xl"></i>
                </div>
                <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center justify-center backdrop-blur-md animate-pulse">
                    <i class="fa-solid fa-pizza-slice text-rose-400 text-2xl"></i>
                </div>
            </div>
        </main>

        <footer class="max-w-7xl mx-auto px-6 py-12 border-t border-slate-900">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} SpendWise. Built for better financial life.
                </p>
                <div class="flex gap-6 text-slate-500 text-lg">
                    <a href="https://github.com/mahardk" class="hover:text-violet-400 transition"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="hover:text-violet-400 transition"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="https://www.instagram.com/__mahardk/" class="hover:text-violet-400 transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>