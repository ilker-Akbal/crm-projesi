<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Contact;   // Gerçek veri ekleyince açın
// use App\Models\Company;   // create() sayfasındaki şirket listesi için

class ContactController extends Controller
{
    /** GET /contacts  ─ Liste  */
    public function index()
    {
        // Şimdilik boş koleksiyon gönderiyoruz
        // $contacts = Contact::with('company')->get();
        $contacts = collect();

        return view('contacts.index', compact('contacts'));
    }

    /** GET /contacts/create  ─ Form  */
    public function create()
    {
        // Formda “Bağlı Şirket” seçilecekse:
        // $companies = Company::orderBy('company_name')->get();
        $companies = collect();

        return view('contacts.create', compact('companies'));
    }

    /* Diğer CRUD metodlarını daha sonra doldurabilirsiniz */
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
