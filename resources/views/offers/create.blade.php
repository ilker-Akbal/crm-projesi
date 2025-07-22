{{-- resources/views/offers/create.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Offer</h3></div>

      <form action="{{ route('offers.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          {{-- giriş yapan kullanıcının customer_id’si --}}
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="order_id">Order (optional)</label>
                <select name="order_id" id="order_id" class="form-control">
                  <option value="">-- select --</option>
                  @foreach($orders as $o)
                    <option value="{{ $o->id }}" {{ old('order_id')==$o->id?'selected':'' }}>
                      #{{ $o->id }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="offer_date">Offer Date</label>
                <input type="date" name="offer_date" id="offer_date"
                       class="form-control"
                       value="{{ old('offer_date', today()->toDateString()) }}">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="valid_until">Valid Until</label>
                <input type="date" name="valid_until" id="valid_until"
                       class="form-control" value="{{ old('valid_until') }}">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="status">Status</label>
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
          <h5>Offer Items</h5>

          <div id="items-container">
            @php $oldItems = old('items', []); @endphp

            @foreach($oldItems as $i => $item)
              <div class="row mb-2 item-row">
                <div class="col-md-5">
                  <select name="items[{{ $i }}][product_id]" class="form-control" required>
                    <option value="">-- Select Product --</option>
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
                         value="{{ $item['amount'] ?? 1 }}" placeholder="Qty" required>
                </div>
                <div class="col-md-3">
                  <input type="number" name="items[{{ $i }}][unit_price]" min="0" step="0.01"
                         class="form-control" value="{{ $item['unit_price'] ?? 0 }}"
                         placeholder="Unit Price" required>
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-danger remove-item">&times;</button>
                </div>
              </div>
            @endforeach
          </div>

          <button type="button" id="add-item" class="btn btn-sm btn-outline-primary">
            + Add Item
          </button>

          <div class="form-group mt-3">
            <label for="total_amount">Total Amount (optional)</label>
            <input type="number" step="0.01" name="total_amount" id="total_amount"
                   class="form-control" value="{{ old('total_amount', 0) }}">
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('offers.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
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
    const c     = document.getElementById('items-container');
    const idx   = c.querySelectorAll('.item-row').length;
    const row   = document.createElement('div');
    row.className = 'row mb-2 item-row';
    row.innerHTML = `
      <div class="col-md-5">
        <select name="items[${idx}][product_id]" class="form-control" required>
          <option value="">-- Select Product --</option>
          @foreach($products as $prod)
            <option value="{{ $prod['id'] }}">{{ $prod['product_name'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="items[${idx}][amount]" min="1"
               class="form-control" placeholder="Qty" required>
      </div>
      <div class="col-md-3">
        <input type="number" name="items[${idx}][unit_price]" min="0" step="0.01"
               class="form-control" placeholder="Unit Price" required>
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
