@extends('layouts.app')
@section('content')
<section class="content"><div class="container-fluid">
  <div class="card card-outline card-primary mb-3">
    <div class="card-header d-flex justify-content-between">
      <h3 class="card-title">Seri Numaraları</h3>
      <a href="{{ route('product_serials.create') }}"
         class="btn btn-sm btn-primary">Yeni Ekle</a>
    </div>
  </div>
  <div class="card card-outline card-primary">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>#</th><th>Ürün</th><th>Seri No</th><th>Durum</th><th>İşlem</th>
          </tr>
        </thead>
        <tbody>
          @forelse($serials as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->product->product_name }}</td>
            <td>{{ $s->serial_number }}</td>
            <td>{{ ucfirst($s->status) }}</td>
            <td>
              <form method="POST"
                    action="{{ route('product_serials.destroy',$s) }}"
                    onsubmit="return confirm('Silinsin mi?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Sil</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center">Kayıt yok</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div></section>
@endsection
