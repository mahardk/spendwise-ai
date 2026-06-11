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

        return view('dashboard', compact(
            'balance', 'totalIncome', 'totalExpense', 'recentTransactions'
        ));
        
    }
}