<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'name'       => [
                'required', 'string', 'max:255',
                Rule::unique('contacts')->where(fn($q) =>
                    $q->where('company_id', $request->company_id)
                ),
            ],
            'position'   => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255|unique:contacts,email',
            'phone'      => 'required|digits:11|unique:contacts,phone',
        ], [
            'name.unique'  => 'Bu isim zaten bu firmada kayıtlı.',
            'phone.unique' => 'Bu telefon numarası başka bir kişiye ait.',
            'email.unique' => 'Bu e-posta başka bir kişiye ait.',
        ]);

        Contact::create($data);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Kişi başarıyla eklendi.');
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
            'name'       => [
                'required', 'string', 'max:255',
                Rule::unique('contacts')->ignore($contact->id)->where(fn($q) =>
                    $q->where('company_id', $request->company_id)
                ),
            ],
            'position'   => 'nullable|string|max:255',
            'email'      => [
                'nullable', 'email', 'max:255',
                Rule::unique('contacts', 'email')->ignore($contact->id),
            ],
            'phone'      => [
                'required', 'digits:11',
                Rule::unique('contacts', 'phone')->ignore($contact->id),
            ],
        ], [
            'name.unique'  => 'Bu isim zaten bu firmada kayıtlı.',
            'phone.unique' => 'Bu telefon numarası başka bir kişiye ait.',
            'email.unique' => 'Bu e-posta başka bir kişiye ait.',
        ]);

        $contact->update($data);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Kişi bilgileri güncellendi.');
    }

    // Göster
    public function show(Contact $contact)
    {
        $contact->load('company');
        return view('contacts.show', compact('contact'));
    }

    // Sil
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Kişi başarıyla silindi.');
    }
}
