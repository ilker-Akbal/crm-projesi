@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Ürün Düzenle #{{ $product->id }}</h3>
      </div>

      <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf @method('PUT')

        @php
          $lastPrice = $product->prices->last();
          $lastStock = $product->stocks->last();
        @endphp

        <div class="card-body">

          {{-- ---------- Temel Bilgiler ---------- --}}
          <div class="form-group">
            <label for="product_name">Ürün Adı *</label>
            <input  type="text"
                    name="product_name"
                    id="product_name"
                    class="form-control @error('product_name') is-invalid @enderror"
                    value="{{ old('product_name', $product->product_name) }}"
                    required>
            @error('product_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="explanation">Açıklama</label>
            <textarea name="explanation"
                      id="explanation"
                      rows="3"
                      class="form-control">@{{ old('explanation', $product->explanation) }}</textarea>
          </div>

          <hr>

          {{-- ---------- Fiyat ---------- --}}
          <div class="form-group">
            <label for="price">
              Fiyat&nbsp;(₺) <small class="text-muted">(değişiklik yoksa boş bırakın)</small>
            </label>
            <input  type="number"
                    step="0.01"
                    name="price"
                    id="price"
                    class="form-control text-end"
                    placeholder="Mevcut: {{ $lastPrice? number_format($lastPrice->price,2) : 'N/A' }}">
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <hr>

          {{-- ---------- Stok ---------- --}}
          <h5>Stok Düzenle</h5>
          <div class="form-row">
            <div class="col-md-3 mb-3">
              <label for="stock_quantity">Toplam</label>
              <input type="number"
                     name="stock_quantity"
                     id="stock_quantity"
                     class="form-control text-end"
                     placeholder="Mevcut: {{ $lastStock? $lastStock->stock_quantity : 'N/A' }}">
            </div>
{{-- -------- Bloke alanı -------- --}}
<div class="col-md-3 mb-3">
  <label for="blocked_stock">Bloke</label>
  <input  type="number"
          name="blocked_stock"
          id="blocked_stock"
          class="form-control text-end"
          placeholder="Mevcut: {{ $lastStock? $lastStock->blocked_stock : 'N/A' }}"
          oninput="renderSerialRows()">
</div>

{{-- -------- Seri satırları konteyneri -------- --}}
<div class="col-12 mb-3" id="serialRows" style="display:none">
  <label>Bloke edilecek seri numaraları</label>
  <div id="rowsWrapper"></div>
  <small class="form-text text-muted">
    Her satırda bir seri seçin • toplam <span id="needQtyTxt">0</span> adet
  </small>
</div>

@push('scripts')
<script>
/* ---------- Veriyi blade’den JS’e gönder ---------- */
const allSerials = @json($availableSerials->pluck('serial_number'));

function renderSerialRows() {
  const qtyInput  = document.getElementById('blocked_stock');
  const wrapper   = document.getElementById('rowsWrapper');
  const box       = document.getElementById('serialRows');
  const needTxt   = document.getElementById('needQtyTxt');

  const qty = parseInt(qtyInput.value || 0);
  needTxt.textContent = qty;

  // kutuyu göster/gizle
  box.style.display = qty > 0 ? 'block' : 'none';
  wrapper.innerHTML = '';           // temizle

  if (qty <= 0) return;

  for (let i = 0; i < qty; i++) {
    const sel = document.createElement('select');
    sel.name  = `blocked_serials[${i}]`;
    sel.className = 'form-control mb-2 serial-select';

    // placeholder option
    sel.append(new Option('-- seçin --', '', true, false));

    allSerials.forEach(sn => {
      sel.append(new Option(sn, sn));
    });

    sel.addEventListener('change', handleUniqueSelection);
    wrapper.appendChild(sel);
  }
}

/* ---------- Seçilen seriyi diğer satırlardan kaldır ---------- */
function handleUniqueSelection(e) {
  const selects = document.querySelectorAll('.serial-select');
  const chosen  = [...selects].map(s => s.value).filter(v => v);

  selects.forEach(sel => {
    [...sel.options].forEach(opt => {
      if (opt.value === '') return;           // placeholder
      opt.disabled = chosen.includes(opt.value) && opt.value !== sel.value;
    });
  });
}
</script>
@endpush




            <div class="col-md-3 mb-3">
              <label for="reserved_stock">Rezerve</label>
              <input type="number"
                     name="reserved_stock"
                     id="reserved_stock"
                     class="form-control text-end"
                     placeholder="Mevcut: {{ $lastStock? $lastStock->reserved_stock : 'N/A' }}">
            </div>

            <div class="col-md-3 mb-3 d-flex align-items-end">
              <span class="form-text text-muted">
                Boş bırakırsanız mevcut değerler korunur
              </span>
            </div>
          </div>

        </div>{{-- /.card-body --}}

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('products.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Güncelle</button>
        </div>
      </form>
    </div>

  </div>
</section>
@endsection
