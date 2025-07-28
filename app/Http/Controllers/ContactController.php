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
    $request->validate([
        'name' => ['required', 'regex:/^[A-Za-zÇçĞğİıÖöŞşÜü\s]+$/u', 'min:2'],
        'phone' => ['required', 'digits:11'],
        'email' => ['nullable', 'email'],
        'company_id' => ['nullable', 'exists:companies,id'],
        'position' => ['nullable', 'string'],
    ], [
        'name.regex' => 'Ad sadece harf ve boşluk içerebilir.',
        'phone.digits' => 'Telefon numarası 11 haneli olmalıdır.',
    ]);

    // Kişiyi oluştur
    Contact::create($request->all());

    return redirect()->route('contacts.index')->with('success', 'Kişi başarıyla eklendi.');
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
    $request->validate([
        'name' => ['required', 'regex:/^[A-Za-zÇçĞğİıÖöŞşÜü\s]+$/u', 'min:2'],
        'phone' => ['required', 'digits:11'],
        'email' => ['nullable', 'email'],
        'company_id' => ['nullable', 'exists:companies,id'],
        'position' => ['nullable', 'string'],
    ], [
        'name.regex' => 'Ad sadece harf ve boşluk içerebilir.',
        'phone.digits' => 'Telefon numarası 11 haneli olmalıdır.',
    ]);

    $contact->update($request->all());

    return redirect()->route('contacts.index')->with('success', 'Kişi başarıyla güncellendi.');
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
