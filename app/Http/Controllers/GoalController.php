<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index()
    {
        $goals = auth()->user()->goals()->latest()->get();
        return view('goals.index', compact('goals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'icon'          => 'required|string|max:10',
            'target_amount' => 'required|numeric|min:1000',
            'deadline'      => 'nullable|date|after:today',
        ]);

        $validated['user_id']     = auth()->id();
        $validated['saved_amount'] = 0;

        Goal::create($validated);

        return redirect()->route('goals.index')->with('success', 'Goal berhasil dibuat!');
    }

    public function addSaving(Request $request, Goal $goal)
    {
        abort_if($goal->user_id !== auth()->id(), 403);

        $request->validate(['amount' => 'required|numeric|min:1000']);

        $goal->increment('saved_amount', $request->amount);

        return redirect()->route('goals.index')->with('success', 'Tabungan berhasil ditambahkan!');
    }

    public function destroy(Goal $goal)
    {
        abort_if($goal->user_id !== auth()->id(), 403);
        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Goal dihapus.');
    }
}