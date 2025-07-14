<?php
// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Company;

class ContactController extends Controller
{
    // Liste
    public function index()
    {
        $contacts = Contact::with('company')->get();
        return view('contacts.index', compact('contacts'));
    }

    // Yeni
    public function create()
    {
        $companies = Company::orderBy('Company_name')->get();
        return view('contacts.create', compact('companies'));
    }

    // Kaydet
    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'name'       => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:50',
        ]);

        Contact::create($data);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    // Düzenle
    public function edit(Contact $contact)
    {
        $companies = Company::orderBy('Company_name')->get();
        return view('contacts.edit', compact('contact','companies'));
    }

    // Güncelle
    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'name'       => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:50',
        ]);

        $contact->update($data);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    // Sil
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
}
