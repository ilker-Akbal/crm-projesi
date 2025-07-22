{{-- resources/views/orders/create.blade.php --}}
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

          {{-- ---------- Sipariş Başlığı ---------- --}}
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

            {{-- Sipariş Tarihi --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="order_date">Sipariş Tarihi *</label>
                <input  type="date" name="order_date" id="order_date"
                        class="form-control"
                        value="{{ old('order_date', today()->toDateString()) }}" required>
              </div>
            </div>

            {{-- Teslim Tarihi --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="delivery_date">Teslim Tarihi</label>
                <input  type="date" name="delivery_date" id="delivery_date"
                        class="form-control" value="{{ old('delivery_date') }}">
              </div>
            </div>
          </div>

          <div class="row">
            {{-- Sipariş Türü --}}
            <div class="col-md-6">
              <div class="form-group">
                <label>Sipariş Türü *</label><br>
                <label class="mr-3">
                  <input type="radio" name="order_type" value="sale"
                         {{ old('order_type','sale')=='sale' ? 'checked' : '' }}>
                  Satış
                </label>
                <label>
                  <input type="radio" name="order_type" value="purchase"
                         {{ old('order_type')=='purchase' ? 'checked' : '' }}>
                  Satın Alma
                </label>
              </div>
            </div>

            {{-- Ödeme Durumu --}}
            <div class="col-md-6">
              <div class="form-group">
                <label>
                  <input type="checkbox" name="is_paid" value="1"
                         {{ old('is_paid') ? 'checked' : '' }}>
                  Ödeme tamamlandı
                </label>
              </div>
            </div>
          </div>

          {{-- ---------- Kalemler ---------- --}}
          <h5 class="mt-4">Sipariş Kalemleri</h5>

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

            <button type="button" class="btn btn-sm btn-secondary" id="add-row">
              Satır Ekle
            </button>
          </div>

          <div class="d-flex justify-content-end mt-3">
            <h4>Toplam: <span id="order-total">0.00</span> ₺</h4>
            <input type="hidden" name="total_amount" id="total_amount" value="0">
          </div>
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

  const products = @json($products);   // [{id:…,product_name:…,unit_price:…}, …]
  let rowIndex   = 0;

  function optionList() {
    return products.map(p =>
      `<option value="${p.id}" data-price="${p.unit_price}">${p.product_name}</option>`
    ).join('');
  }

  /* Satır ekle */
  $('#add-row').on('click', () => {
    $('#order-items tbody').append(`
      <tr>
        <td>
          <select name="items[${rowIndex}][product_id]" class="form-control product-select" required>
            <option value="">-- seçiniz --</option>
            ${optionList()}
          </select>
        </td>
        <td>
          <input type="number" name="items[${rowIndex}][amount]" class="form-control amount"
                 min="1" value="1" required>
        </td>
        <td>
          <input type="number" name="items[${rowIndex}][unit_price]" class="form-control unit-price"
                 min="0" step="0.01" value="0" required>
        </td>
        <td class="subtotal text-right">0.00</td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm remove-row">&times;</button>
        </td>
      </tr>
    `);
    rowIndex++;
  });

  /* Satır sil */
  $(document).on('click', '.remove-row', function () {
    $(this).closest('tr').remove();
    recalcTotals();
  });

  /* Ara toplam / toplam hesabı */
  $(document).on('input change', '.amount, .unit-price', recalcTotals);

  function recalcTotals() {
    let total = 0;
    $('#order-items tbody tr').each(function () {
      const qty   = parseFloat($(this).find('.amount').val())     || 0;
      const price = parseFloat($(this).find('.unit-price').val()) || 0;
      const sub   = qty * price;
      $(this).find('.subtotal').text(sub.toFixed(2));
      total += sub;
    });
    $('#order-total').text(total.toFixed(2));
    $('#total_amount').val(total.toFixed(2));
  }

});
</script>
@endpush
