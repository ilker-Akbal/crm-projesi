{{-- resources/views/orders/create.blade.php (yeniden düzenlendi) --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Sipariş Ekle</h3></div>

      <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          {{-- -------- Başlık Satırı -------- --}}
          <div class="row">
            {{-- Firma --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="company_id">Firma (isteğe bağlı)</label>
                <select name="company_id" id="company_id" class="form-control">
                  <option value="">-- seçiniz --</option>
                  @foreach($companies as $c)
                    <option value="{{ $c->id }}" {{ old('company_id')==$c->id ? 'selected' : '' }}>
                      {{ $c->company_name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- Sipariş / Teslim Tarihi --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="order_date">Sipariş Tarihi *</label>
                <input type="date" id="order_date" name="order_date" required
                       class="form-control"
                       value="{{ old('order_date', today()->toDateString()) }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="delivery_date">Teslim Tarihi *</label>
                <input type="date" id="delivery_date" name="delivery_date" required
                       class="form-control @error('delivery_date') is-invalid @enderror"
                       value="{{ old('delivery_date') }}">
                @error('delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          {{-- -------- Sipariş Kalemleri BLOĞU -------- --}}
          <h5 class="mt-3">Sipariş Kalemleri</h5>
          <div class="table-responsive">
            <table class="table table-bordered" id="order-items">
              <thead class="text-center">
                <tr>
                  <th style="width:35%">Ürün</th>
                  <th style="width:15%">Miktar</th>
                  <th style="width:20%">Birim Fiyatı</th>
                  <th style="width:20%">Ara Toplam</th>
                  <th style="width:10%"></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <button type="button" class="btn btn-sm btn-secondary" id="add-row">Satır Ekle</button>
          </div>

          {{-- -------- Sipariş Türü -------- --}}
          <div class="form-group mt-4">
            <label>Sipariş Türü *</label><br>
            <label class="mr-3">
              <input type="radio" name="order_type" value="sale" {{ old('order_type','sale')=='sale' ? 'checked' : '' }}> Satış
            </label>
            <label>
              <input type="radio" name="order_type" value="purchase" {{ old('order_type')=='purchase' ? 'checked' : '' }}> Satın Alma
            </label>
          </div>

          {{-- -------- Ödeme + Toplam -------- --}}
          <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
              <label class="mb-0">
                <input type="checkbox" name="is_paid" value="1" {{ old('is_paid') ? 'checked' : '' }}> Ödeme tamamlandı
              </label>
            </div>
            <h4 class="mb-0">Toplam: <span id="order-total">0.00</span> ₺</h4>
          </div>
          <input type="hidden" name="total_amount" id="total_amount" value="0">
        </div>

        <div class="card-footer">
          @include('partials.form-buttons')
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
$(function () {

  const products = @json($products);   // [{id, product_name, unit_price, stock}, …]
  let rowIndex   = 0;

  /* ---------- Sipariş türü yardımcıları ---------- */
  function isSale() {
    return $('input[name="order_type"]:checked').val() === 'sale';
  }

  $('input[name="order_type"]').on('change', () => {
    $('#order-items tbody tr').each(function () {
      toggleStockLimit($(this));       // tür değişince satırları baştan değerlendir
    });
  });

  /* ---------- Ürün listesini hazırla ---------- */
  function optionList() {
    return products.map(p =>
      `<option value="${p.id}"
               data-price="${p.unit_price}"
               data-stock="${p.stock}">
         ${p.product_name}
       </option>`
    ).join('');
  }

  /* ---------- Satır ekle ---------- */
  $('#add-row').on('click', () => {
    $('#order-items tbody').append(`
      <tr>
        <td>
          <select name="items[${rowIndex}][product_id]"
                  class="form-control product-select" required>
            <option value="">-- seçiniz --</option>
            ${optionList()}
          </select>
        </td>
        <td>
          <input type="number" name="items[${rowIndex}][amount]"
                 class="form-control amount" min="1" value="1" required>
          <small class="text-muted stock-info"></small>
        </td>
        <td>
          <input type="text"  class="form-control-plaintext unit-price-view" value="0" readonly>
          <input type="hidden" name="items[${rowIndex}][unit_price]"
                 class="unit-price-hidden" value="0">
        </td>
        <td class="subtotal text-right">0.00</td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm remove-row">&times;</button>
        </td>
      </tr>
    `);

    const newRow = $('#order-items tbody tr').last();
    toggleStockLimit(newRow);          // eklenen satır için ilk limit ayarı
    rowIndex++;
    refreshDisabledOptions();
  });

  /* ---------- Satır sil ---------- */
  $(document).on('click', '.remove-row', function () {
    $(this).closest('tr').remove();
    recalcTotals();
    refreshDisabledOptions();
  });

  /* ---------- Ürün seçildiğinde fiyat + stok işlemleri ---------- */
  $(document).on('change', '.product-select', function () {
    const currVal = this.value;
    const row     = $(this).closest('tr');

    if (!currVal) {                    // seçim boşaltıldı
      clearRow(row);
      refreshDisabledOptions();
      return;
    }

    /* Kopya kontrolü */
    const duplicate = $('.product-select')
                        .not(this)
                        .filter((i, el) => $(el).val() === currVal)
                        .length;
    if (duplicate) {
      alert('Bu ürün zaten eklendi. Aynı ürünü tekrar seçemezsiniz.');
      $(this).val('');
      clearRow(row);
      refreshDisabledOptions();
      return;
    }

    /* Fiyat + stok bilgisi */
    const opt   = this.selectedOptions[0];
    const price = parseFloat(opt.dataset.price || 0);
    const stock = parseInt(opt.dataset.stock || 0, 10);

    row.find('.unit-price-view').val(price.toFixed(2));
    row.find('.unit-price-hidden').val(price);
    row.find('.amount').val(1).attr('data-stock', stock);
    row.find('.stock-info').text(`Stok: ${stock}`);

    toggleStockLimit(row);
    recalcTotals();
    refreshDisabledOptions();
  });

  /* ---------- Miktar değişiminde ---------- */
  $(document).on('input', '.amount', function () {
    const row = $(this).closest('tr');
    toggleStockLimit(row);             // stok sınırı hâlâ geçerli mi?
    recalcTotals();
  });

  /* ---------- Toplam hesap ---------- */
  function recalcTotals() {
    let total = 0;
    $('#order-items tbody tr').each(function () {
      const qty   = parseFloat($(this).find('.amount').val())            || 0;
      const price = parseFloat($(this).find('.unit-price-hidden').val()) || 0;
      const sub   = qty * price;
      $(this).find('.subtotal').text(sub.toFixed(2));
      total += sub;
    });
    $('#order-total').text(total.toFixed(2));
    $('#total_amount').val(total.toFixed(2));
  }

  /* ---------- Yardımcılar ---------- */
  function clearRow(row) {
    row.find('.unit-price-view').val('0.00');
    row.find('.unit-price-hidden').val(0);
    row.find('.amount').val(1).removeAttr('max').removeAttr('data-stock');
    row.find('.stock-info').text('');
    recalcTotals();
  }

  /* Satışta max, satın-almada sınırsız */
  function toggleStockLimit(row) {
    const stock = parseInt(row.find('.amount').attr('data-stock') || 0, 10);
    if (isSale() && stock) {
      row.find('.amount').attr('max', stock);
      if (+row.find('.amount').val() > stock) {
        row.find('.amount').val(stock);
      }
    } else {
      row.find('.amount').removeAttr('max');
    }
  }

  /* Seçilmiş ürünleri diğer satırlarda disable et */
  function refreshDisabledOptions() {
    const selected = $('.product-select').map((i, el) => $(el).val()).get();
    $('.product-select').each(function () {
      const current = $(this).val();
      $(this).find('option').each(function () {
        const val = $(this).val();
        if (!val) return;
        $(this).prop('disabled', selected.includes(val) && val !== current);
      });
    });
  }

});
</script>

@endpush

