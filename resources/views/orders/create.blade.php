@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Add Order</h3>
      </div>

      <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          {{-- ---------- Order Header ---------- --}}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="customer_id">Customer</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                  <option value="">-- select --</option>
                  @foreach ($customers as $c)
                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                      {{ $c->customer_name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="order_date">Order Date</label>
                <input  type="date"
                        name="order_date"
                        id="order_date"
                        class="form-control"
                        value="{{ old('order_date', today()->toDateString()) }}"
                        required>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="delivery_date">Delivery Date</label>
                <input  type="date"
                        name="delivery_date"
                        id="delivery_date"
                        class="form-control"
                        value="{{ old('delivery_date') }}">
              </div>
            </div>
          </div>

          {{-- ---------- Items ---------- --}}
          <h5 class="mt-4">Order Items</h5>

          <div class="table-responsive">
            <table class="table table-bordered" id="order-items">
              <thead class="text-center">
                <tr>
                  <th style="width:35%">Product</th>
                  <th style="width:15%">Amount</th>
                  <th style="width:20%">Unit&nbsp;Price</th>
                  <th style="width:20%">Subtotal</th>
                  <th style="width:10%"></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>

            <button type="button" class="btn btn-sm btn-secondary" id="add-row">
              Add Row
            </button>
          </div>

          <div class="d-flex justify-content-end mt-3">
            <h4>Total: <span id="order-total">0.00</span> ₺</h4>
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
$(function () {     // jQuery ready

  let products = @json($products);   // [{id:1,product_name:'X',unit_price:5.5}, …]
  let rowIndex = 0;

  function optionList() {
    return products.map(p =>
      `<option value="${p.id}" data-price="${p.unit_price}">${p.product_name}</option>`
    ).join('');
  }

  /* Satır ekle */
  $('#add-row').on('click', function () {
    $('#order-items tbody').append(`
      <tr>
        <td>
          <select name="items[${rowIndex}][product_id]" class="form-control product-select" required>
            <option value="">-- select --</option>
            ${optionList()}
          </select>
        </td>
        <td>
          <input  type="number" name="items[${rowIndex}][amount]"
                  class="form-control amount" min="1" value="1" required>
        </td>
        <td>
          <input  type="number" name="items[${rowIndex}][unit_price]"
                  class="form-control unit-price" step="0.01"
                  min="0" value="0" required>
        </td>
        <td class="subtotal text-right">0.00</td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm remove-row">&times;</button>
        </td>
      </tr>
    `);
    rowIndex++;
  });

  /* Satır sil & toplam/ara toplam güncelle */
  $(document).on('click', '.remove-row', function () {
    $(this).closest('tr').remove();
    recalcTotals();
  });

  $(document).on('input change', '.amount, .unit-price', recalcTotals);

  function recalcTotals() {
    let total = 0;
    $('#order-items tbody tr').each(function () {
      let qty   = parseFloat($(this).find('.amount').val())      || 0;
      let price = parseFloat($(this).find('.unit-price').val())  || 0;
      let sub   = qty * price;
      $(this).find('.subtotal').text(sub.toFixed(2));
      total += sub;
    });
    $('#order-total').text(total.toFixed(2));
    $('#total_amount').val(total.toFixed(2));
  }

});
</script>
@endpush
