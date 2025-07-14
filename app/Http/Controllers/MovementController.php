<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrentMovement;
use App\Models\CurrentCard;

class MovementController extends Controller
{
    // GET /movements
    public function index()
    {
        $movements = CurrentMovement::with('currentCard.customer')
            ->orderBy('departure_date','desc')
            ->get();

        return view('movements.index', compact('movements'));
    }

    // GET /movements/create
    public function create()
    {
        $accounts = CurrentCard::with('customer')
            ->orderBy('opening_date','desc')
            ->get();

        return view('movements.create', compact('accounts'));
    }

    // POST /movements
    public function store(Request $request)
    {
        $data = $request->validate([
            'current_id'     => 'required|exists:current_cards,id',
            'departure_date' => 'required|date',
            'movement_type'  => 'required|in:Debit,Credit',
            'amount'         => 'required|numeric|min:0',
            'explanation'    => 'nullable|string',
        ]);

        CurrentMovement::create($data);

        return redirect()
            ->route('movements.index')
            ->with('success','Movement recorded successfully.');
    }

    // GET /movements/{movement}
    public function show(CurrentMovement $movement)
    {
        $movement->load('currentCard.customer');
        return view('movements.show', compact('movement'));
    }

    // GET /movements/{movement}/edit
    public function edit(CurrentMovement $movement)
    {
        $accounts = CurrentCard::with('customer')->get();
        return view('movements.edit', compact('movement','accounts'));
    }

    // PUT /movements/{movement}
    public function update(Request $request, CurrentMovement $movement)
    {
        $data = $request->validate([
            'current_id'     => 'required|exists:current_cards,id',
            'departure_date' => 'required|date',
            'movement_type'  => 'required|in:Debit,Credit',
            'amount'         => 'required|numeric|min:0',
            'explanation'    => 'nullable|string',
        ]);

        $movement->update($data);

        return redirect()
            ->route('movements.index')
            ->with('success','Movement updated successfully.');
    }

    // DELETE /movements/{movement}
    public function destroy(CurrentMovement $movement)
    {
        $movement->delete();

        return redirect()
            ->route('movements.index')
            ->with('success','Movement deleted successfully.');
    }
}
