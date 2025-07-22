@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Müşteriler</h2>

    <div class="mb-3">
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Yeni Müşteri Ekle</a>
        <a href="{{ url('/admin') }}" class="btn btn-sm btn-secondary">Geri</a>
    </div>

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Ad</th>
                <th scope="col">Tür</th>
                <th scope="col">E-posta</th>
                <th scope="col" style="width: 200px">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <th scope="row">{{ $customer->id }}</th>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ ucfirst($customer->customer_type) }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info">Görüntüle</a>
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning">Düzenle</a>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="btn btn-sm btn-danger" 
                                onclick="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?')"
                            >
                                Sil
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Kayıtlı müşteri bulunamadı.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
