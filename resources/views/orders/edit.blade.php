{{-- resources/views/orders/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Sipariş Düzenle #' . $order->id)

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">
          Sipariş Düzenle #{{ $order->id }}
          @if($order->company?->company_name)
            – {{ $order->company->company_name }}
          @endif
        </h3>
      </div>

      <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf @method('PUT')

        <div class="card-body">
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          {{-- Firma Adı (okunur) --}}
          @if($order->company?->company_name)
          <div class="form-group">
            <label>Firma</label>
            <input type="text" class="form-control" 
                   value="{{ $order->company->company_name }}" readonly>
          </div>
          @endif

          {{-- Sipariş Türü --}}
          <div class="form-group">
            <label>Sipariş Türü *</label><br>
            <label class="mr-3">
              <input type="radio" name="order_type" value="sale"
                     {{ old('order_type', $order->order_type) == 'sale' ? 'checked' : '' }}>
              Satış
            </label>
            <label>
              <input type="radio" name="order_type" value="purchase"
                     {{ old('order_type', $order->order_type) == 'purchase' ? 'checked' : '' }}>
              Satın Alma
            </label>
            @error('order_type') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          {{-- Tarihler --}}
          <div class="form-group">
            <label for="order_date">Sipariş Tarihi</label>
            <input type="date" id="order_date" name="order_date"
                   class="form-control @error('order_date') is-invalid @enderror"
                   value="{{ old('order_date', $order->order_date?->format('Y-m-d')) }}" required>
            @error('order_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="delivery_date">Teslim Tarihi</label>
            <input type="date" id="delivery_date" name="delivery_date"
                   class="form-control @error('delivery_date') is-invalid @enderror"
                   value="{{ old('delivery_date', optional($order->delivery_date)->format('Y-m-d')) }}">
            @error('delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <hr>

          {{-- Sipariş Kalemleri (ürün satırları) --}}
          <h5>Sipariş Kalemleri</h5>
          <div id="items-container">
            @php
              $oldItems = old('items', $order->products->map(fn($p)=>[
                  'product_id' => $p->id,
                  'amount'     => $p->pivot->amount,
                  'unit_price' => $p->pivot->unit_price,
              ])->toArray());
            @endphp

            @forelse($oldItems as $i => $item)
              <div class="row mb-2 item-row">
                {{-- Ürün --}}
                <div class="col-md-5">
                  <select name="items[{{ $i }}][product_id]"
                          class="form-control product-select">
                    <option value="">-- Ürün Seçiniz --</option>
                    @foreach($products as $prod)
                      <option value="{{ $prod['id'] }}"
                              data-price="{{ $prod['unit_price'] }}"
                              data-stock="{{ $prod['stock'] }}"
                              {{ $item['product_id'] == $prod['id'] ? 'selected' : '' }}>
                        {{ $prod['product_name'] }}
                      </option>
                    @endforeach
                  </select>
                </div>

                {{-- Adet --}}
                <div class="col-md-3">
                  <input type="number" class="form-control amount" min="1"
                         name="items[{{ $i }}][amount]"
                         value="{{ $item['amount'] }}">
                  <small class="text-muted stock-info"></small>
                </div>

                {{-- Fiyat (readonly) --}}
                <div class="col-md-3">
                  <input type="text" class="form-control-plaintext unit-price-view"
                         value="{{ number_format($item['unit_price'],2) }}" readonly>
                  <input type="hidden" name="items[{{ $i }}][unit_price]"
                         class="unit-price-hidden"
                         value="{{ $item['unit_price'] }}">
                </div>

                {{-- Sil butonu --}}
                <div class="col-md-1">
                  <button type="button" class="btn btn-danger remove-item">&times;</button>
                </div>
              </div>
            @empty
              {{-- hiç ürün yok --}}
            @endforelse
          </div>

          <button type="button" id="add-item" class="btn btn-sm btn-outline-primary">
            + Kalem Ekle
          </button>

          {{-- Ödeme Durumu --}}
          <div class="form-group mt-3">
            <label>
              <input type="checkbox" name="is_paid" value="1"
                     {{ old('is_paid', $order->is_paid) ? 'checked' : '' }}>
              Ödeme tamamlandı
            </label>
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('orders.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Siparişi Güncelle</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
$(function () {

  const products = @json($products);
  let rowIndex = $('#items-container .item-row').length;

  function isSale() {
    return $('input[name="order_type"]:checked').val() === 'sale';
  }

  function optionList() {
    return products.map(p =>
      `<option value="${p.id}" data-price="${p.unit_price}" data-stock="${p.stock}">
         ${p.product_name}
       </option>`
    ).join('');
  }

  function clearRow(row) {
    row.find('.unit-price-view').val('0.00');
    row.find('.unit-price-hidden').val(0);
    row.find('.amount').val(1).removeAttr('max').removeAttr('data-stock');
    row.find('.stock-info').text('');
  }

  function refreshDisabledOptions() {
    const selected = $('.product-select').map((i,el)=> $(el).val()).get();
    $('.product-select').each(function () {
      const current = $(this).val();
      $(this).find('option').each(function () {
        const val = $(this).val();
        if (!val) return;
        $(this).prop('disabled', selected.includes(val) && val !== current);
      });
    });
  }

  function recalcTotals() {
    let total = 0;
    $('#items-container .item-row').each(function () {
      const qty = parseFloat($(this).find('.amount').val()) || 0;
      const price = parseFloat($(this).find('.unit-price-hidden').val()) || 0;
      total += qty * price;
    });
    $('#total_amount').val(total.toFixed(2));
    $('#order-total').text(total.toFixed(2));
  }

  function toggleStockLimit(row) {
    const stock = parseInt(row.find('.amount').attr('data-stock') || 0, 10);
    if (isSale() && stock) {
      row.find('.amount').attr('max', stock);
      if (+row.find('.amount').val() > stock) row.find('.amount').val(stock);
    } else {
      row.find('.amount').removeAttr('max');
    }
  }

  function populateRow(row) {
    const select = row.find('.product-select');
    const val = select.val();
    if (!val) return;

    const opt = select.find('option:selected')[0];
    const price = parseFloat(opt.dataset.price || 0);
    const stock = parseInt(opt.dataset.stock || 0, 10);

    row.find('.unit-price-view').val(price.toFixed(2));
    row.find('.unit-price-hidden').val(price);
    row.find('.amount')
        .attr('data-stock', stock)
        .val(row.find('.amount').val() || 1);
    row.find('.stock-info').text(`Stok: ${stock}`);

    toggleStockLimit(row);
  }

  $('#add-item').on('click', () => {
    $('#items-container').append(`
      <div class="row mb-2 item-row">
        <div class="col-md-5">
          <select name="items[${rowIndex}][product_id]" class="form-control product-select">
            <option value="">-- Ürün Seçiniz --</option>${optionList()}
          </select>
        </div>
        <div class="col-md-3">
          <input type="number" name="items[${rowIndex}][amount]" class="form-control amount" min="1" value="1">
          <small class="text-muted stock-info"></small>
        </div>
        <div class="col-md-3">
          <input type="text" class="form-control-plaintext unit-price-view" readonly value="0.00">
          <input type="hidden" name="items[${rowIndex}][unit_price]" class="unit-price-hidden" value="0">
        </div>
        <div class="col-md-1">
          <button type="button" class="btn btn-danger remove-item">&times;</button>
        </div>
      </div>
    `);
    const newRow = $('#items-container .item-row').last();
    populateRow(newRow);
    rowIndex++;
    refreshDisabledOptions();
  });

  $(document).on('click', '.remove-item', function () {
    $(this).closest('.item-row').remove();
    refreshDisabledOptions();
    recalcTotals();
  });

  $(document).on('change', '.product-select', function () {
    const row = $(this).closest('.item-row');
    const val = this.value;
    const dup = $('.product-select').not(this).filter((i,el)=> $(el).val() === val).length;
    if (dup) {
      alert('Bu ürün zaten eklendi. Aynı ürünü tekrar seçemezsiniz.');
      $(this).val('');
      clearRow(row);
      refreshDisabledOptions();
      recalcTotals();
      return;
    }
    populateRow(row);
    refreshDisabledOptions();
    recalcTotals();
  });

  $(document).on('input', '.amount', function () {
    const row = $(this).closest('.item-row');
    toggleStockLimit(row);
    recalcTotals();
  });

  function initExistingRows() {
    $('#items-container .item-row').each(function () {
      populateRow($(this));
    });
    refreshDisabledOptions();
    recalcTotals();
  }

  initExistingRows();
});
</script>
@endpush
