<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\CurrentMovement;   // Gerçek veri eklediğinizde açın
// use App\Models\CurrentCard;

class MovementController extends Controller
{
    /** GET /movements  */
    public function index()
    {
        // $movements = CurrentMovement::with('currentCard.customer')->get();
        $movements = collect();      // Şimdilik boş
        return view('movements.index', compact('movements'));
    }

    /** GET /movements/create */
    public function create()
    {
        // $accounts = CurrentCard::with('customer')->get();
        $accounts = collect();       // Şimdilik boş
        return view('movements.create', compact('accounts'));
    }

    /** POST /movements  – sadece test amaçlı */
    public function store(Request $request)
    {
        // İleride validasyon + kayıt eklersiniz
        return back()->with('success', 'Demo: hareket kaydedilmedi, yalnızca sayfa yüklendi.');
    }

    /* Diğer metotlar boş bırakıldı */
    public function show($id)    {}
    public function edit($id)    {}
    public function update(Request $r, $id) {}
    public function destroy($id) {}
}
