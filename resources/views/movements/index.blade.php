@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    <div class="card card-outline card-primary">
      {{-- Başlık + Yeni Kayıt --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Hesap Hareketleri</h3>
        <a href="{{ route('movements.create') }}" class="btn btn-sm btn-primary">Hareket Ekle</a>
      </div>

      {{-- ---- Filtre Formu ---- --}}
      <form method="GET" class="p-3 border-bottom">
        <div class="row g-2">
          <div class="col-sm">
            <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="Başlangıç Tarihi">
          </div>
          <div class="col-sm">
            <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="Bitiş Tarihi">
          </div>
          <div class="col-sm">
            <select name="type" class="form-select">
              <option value="">Tüm Türler</option>
              <option value="Debit"  @selected(request('type')=='Debit') >Borç</option>
              <option value="Credit" @selected(request('type')=='Credit')>Alacak</option>
            </select>
          </div>
          <div class="col-sm">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Açıklamada Ara">
          </div>
          <div class="col-auto">
            <button class="btn btn-primary">Filtrele</button>
          </div>
        </div>
      </form>

      {{-- ---- Tablo ---- --}}
      <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle text-nowrap">
          <thead class="table-light">
            <tr>
              
              <th>Hesap</th>
              <th>Tarih</th>
              <th class="text-end">Borç</th>
              <th class="text-end">Alacak</th>
              <th class="text-end">Bakiye</th>
              <th>Açıklama</th>
              <th style="width:140px">İşlemler</th>
            </tr>
          </thead>
          <tbody>
            @php $running = 0; @endphp

            @forelse($movements as $m)
              @php
                $delta   = $m->movement_type === 'Debit' ? -$m->amount : $m->amount;
                $running+= $delta;
              @endphp
              <tr>
                
                <td>{{ $m->currentCard->customer->customer_name }} ({{ $m->current_id }})</td>
                <td>{{ \Carbon\Carbon::parse($m->departure_date)->format('d.m.Y') }}</td>

                {{-- Borç / Alacak renkli hücreler --}}
                <td class="text-end text-danger">
                  {{ $m->movement_type=='Debit' ? number_format($m->amount,2) : '' }}
                </td>
                <td class="text-end text-success">
                  {{ $m->movement_type=='Credit' ? number_format($m->amount,2) : '' }}
                </td>

                <td class="text-end fw-semibold">{{ number_format($running,2) }}</td>
                <td>{{ $m->explanation }}</td>

                <td>
                  <a href="{{ route('movements.show',$m) }}" class="btn btn-xs btn-info">Görüntüle</a>
                  <a href="{{ route('movements.edit',$m) }}" class="btn btn-xs btn-warning">Düzenle</a>
                  <form action="{{ route('movements.destroy',$m) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Silinsin mi?')" class="btn btn-xs btn-danger">Sil</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="8" class="text-center">Hiç hareket bulunamadı.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Sayfalama (paginate kullanıyorsanız) --}}
      @if(method_exists($movements,'links'))
        <div class="card-footer">{{ $movements->links() }}</div>
      @endif
    </div>
  </div>
</section>
@endsection
