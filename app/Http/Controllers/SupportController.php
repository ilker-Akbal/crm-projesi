<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\SupportRequest;  // Gerçek veriye geçince açarsınız

class SupportController extends Controller
{
    /** GET /support ─ Liste */
    public function index()
    {
        $supports = collect();          // şimdilik boş koleksiyon
        return view('support.index', compact('supports'));
    }

    /** GET /support/create ─ Form */
    public function create()
    {
        $customers = collect();         // ileride Customer::all()
        return view('support.create', compact('customers'));
    }

    /** GET /support/pending */
    public function pending()
    {
        $supports = collect();
        return view('support.pending', compact('supports'));
    }

    /** GET /support/resolved */
    public function resolved()
    {
        $supports = collect();
        return view('support.resolved', compact('supports'));
    }

    /* Aşağıdaki metodlar şimdilik boş; CRUD’a geçtiğinizde doldurursunuz */
    public function store(Request $r)   {}
    public function show($id)          {}
    public function edit($id)          {}
    public function update(Request $r,$id) {}
    public function destroy($id)       {}
}
