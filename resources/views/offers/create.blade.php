{{-- resources/views/offers/create.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Teklif Ekle</h3></div>

      <form action="{{ route('offers.store') }}" method="POST">
        @csrf
        <div class="card-body">
          

          {{-- giriş yapan kullanıcının customer_id’si --}}
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          <div class="row">
            {{-- Şirket (opsiyonel) --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="company_id">Şirket (opsiyonel)</label>
                <select name="company_id" id="company_id" class="form-control">
                  <option value="">-- seçiniz --</option>
                  @foreach($companies as $c)
                    <option value="{{ $c->id }}"
                      {{ old('company_id')==$c->id ? 'selected' : '' }}>
                      {{ $c->company_name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- Sipariş (opsiyonel) --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="order_id">Sipariş (opsiyonel)</label>
                <select name="order_id" id="order_id" class="form-control">
                  <option value="">-- seçiniz --</option>
                  @foreach($orders as $o)
                    <option value="{{ $o->id }}" {{ old('order_id')==$o->id?'selected':'' }}>
                      #{{ $o->id }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- Teklif / Geçerlilik Tarihleri --}}
            <div class="col-md-2">
              <div class="form-group">
                <label for="offer_date">Teklif Tarihi</label>
                <input type="date" name="offer_date" id="offer_date"
                       class="form-control"
                       value="{{ old('offer_date', today()->toDateString()) }}">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="valid_until">Geçerlilik Tarihi</label>
                <input type="date" name="valid_until" id="valid_until"
                       class="form-control" value="{{ old('valid_until') }}">
              </div>
            </div>

            {{-- Durum --}}
            <div class="col-md-4 mt-3">
              <div class="form-group">
                <label for="status">Durum</label>
                <select name="status" id="status" class="form-control" required>
                  @foreach(['hazırlanıyor','gönderildi','kabul','reddedildi'] as $st)
                    <option value="{{ $st }}" {{ old('status',$st)==''.$st ? 'selected' : '' }}>
                      {{ ucfirst($st) }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          {{-- --------- Teklif Kalemleri --------- --}}
          <hr>
          <h5>Teklif Kalemleri</h5>

          <div id="items-container">
            @php $oldItems = old('items', []); @endphp

            @foreach($oldItems as $i => $item)
              <div class="row mb-2 item-row">
                <div class="col-md-5">
                  <select name="items[{{ $i }}][product_id]" class="form-control" required>
                    <option value="">-- Ürün Seçiniz --</option>
                    @foreach($products as $prod)
                      <option value="{{ $prod['id'] }}"
                        {{ ($item['product_id'] ?? null) == $prod['id'] ? 'selected' : '' }}>
                        {{ $prod['product_name'] }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <input type="number" name="items[{{ $i }}][amount]" min="1" class="form-control"
                         value="{{ $item['amount'] ?? 1 }}" placeholder="Adet" required>
                </div>
                <div class="col-md-3">
                  <input type="number" name="items[{{ $i }}][unit_price]" min="0" step="0.01"
                         class="form-control" value="{{ $item['unit_price'] ?? 0 }}"
                         placeholder="Birim Fiyat" required>
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-danger remove-item">&times;</button>
                </div>
              </div>
            @endforeach
          </div>

          <button type="button" id="add-item" class="btn btn-sm btn-outline-primary">
            + Kalem Ekle
          </button>

          <div class="form-group mt-3">
            <label for="total_amount">Toplam Tutar (opsiyonel)</label>
            <input type="number" step="0.01" name="total_amount" id="total_amount"
                   class="form-control" value="{{ old('total_amount', 0) }}">
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('offers.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  /* Yeni satır ekle */
  document.getElementById('add-item').addEventListener('click', () => {
    const c   = document.getElementById('items-container');
    const idx = c.querySelectorAll('.item-row').length;
    const row = document.createElement('div');
    row.className = 'row mb-2 item-row';
    row.innerHTML = `
      <div class="col-md-5">
        <select name="items[\${idx}][product_id]" class="form-control" required>
          <option value="">-- Ürün Seçiniz --</option>
          @foreach($products as $prod)
            <option value="{{ $prod['id'] }}">{{ $prod['product_name'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="items[\${idx}][amount]" min="1"
               class="form-control" placeholder="Adet" required>
      </div>
      <div class="col-md-3">
        <input type="number" name="items[\${idx}][unit_price]" min="0" step="0.01"
               class="form-control" placeholder="Birim Fiyat" required>
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-danger remove-item">&times;</button>
      </div>`;
    c.appendChild(row);
  });

  /* Satır sil */
  document.addEventListener('click', e => {
    if (e.target.matches('.remove-item')) {
      e.target.closest('.item-row').remove();
    }
  });
</script>
@endpush
