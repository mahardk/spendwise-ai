<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $transactions = $user->transactions()
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        $recentTransactions = $transactions->take(5);

        $expenseByCategory = $user->transactions()
            ->where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $monthly = $user->transactions()
            ->selectRaw("DATE_FORMAT(transaction_date, '%Y-%m') as month, type, SUM(amount) as total")
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get()
            ->groupBy('month');

        return view('dashboard', compact(
            'balance', 'totalIncome', 'totalExpense',
            'recentTransactions', 'expenseByCategory', 'monthly'
        ));
    }
}