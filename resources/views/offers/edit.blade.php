{{-- resources/views/offers/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Offer #'.$offer->id)

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Edit Offer #{{ $offer->id }}</h3></div>

      <form action="{{ route('offers.update', $offer) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
          @include('partials.alerts')

          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          <div class="row">
            {{-- Company --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="company_id">Company (optional)</label>
                <select name="company_id" id="company_id"
                        class="form-control @error('company_id') is-invalid @enderror">
                  <option value="">-- select --</option>
                  @foreach($companies as $c)
                    <option value="{{ $c->id }}"
                      {{ old('company_id', $offer->company_id) == $c->id ? 'selected' : '' }}>
                      {{ $c->Company_name }}
                    </option>
                  @endforeach
                </select>
                @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Related Order --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="order_id">Order (optional)</label>
                <select name="order_id" id="order_id"
                        class="form-control @error('order_id') is-invalid @enderror">
                  <option value="">-- select --</option>
                  @foreach($orders as $o)
                    <option value="{{ $o->id }}"
                      {{ old('order_id', $offer->order_id) == $o->id ? 'selected' : '' }}>
                      #{{ $o->id }} — {{ \Carbon\Carbon::parse($o->order_date)->format('Y-m-d') }}
                    </option>
                  @endforeach
                </select>
                @error('order_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Dates --}}
            <div class="col-md-2">
              <div class="form-group">
                <label for="offer_date">Offer Date</label>
                <input type="date" name="offer_date" id="offer_date"
                       class="form-control @error('offer_date') is-invalid @enderror"
                       value="{{ old('offer_date', $offer->offer_date->format('Y-m-d')) }}" required>
                @error('offer_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="valid_until">Valid Until</label>
                <input type="date" name="valid_until" id="valid_until"
                       class="form-control @error('valid_until') is-invalid @enderror"
                       value="{{ old('valid_until', optional($offer->valid_until)->format('Y-m-d')) }}">
                @error('valid_until') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Status --}}
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status"
                        class="form-control @error('status') is-invalid @enderror" required>
                  @foreach(['hazırlanıyor'=>'Hazırlanıyor','gönderildi'=>'Gönderildi','kabul'=>'Kabul','reddedildi'=>'Reddedildi'] as $key=>$label)
                    <option value="{{ $key }}"
                      {{ old('status', $offer->status) === $key ? 'selected' : '' }}>
                      {{ $label }}
                    </option>
                  @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          {{-- ---------- Offer Items ---------- --}}
          <hr>
          <h5>Offer Items</h5>
          <div id="items-container">
            @foreach(old('items', $offer->products->map(fn($p)=>[
                        'product_id'=>$p->id,
                        'amount'=>$p->pivot->amount,
                        'unit_price'=>$p->pivot->unit_price,
                      ])->toArray()) as $i => $item)
              <div class="row mb-2 item-row">
                <div class="col-md-5">
                  <select name="items[{{ $i }}][product_id]" class="form-control" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $prod)
                      <option value="{{ $prod['id'] }}"
                        {{ $item['product_id']==$prod['id'] ? 'selected' : '' }}>
                        {{ $prod['product_name'] }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <input type="number" name="items[{{ $i }}][amount]" min="1"
                         class="form-control" value="{{ $item['amount'] }}" required>
                </div>
                <div class="col-md-3">
                  <input type="number" name="items[{{ $i }}][unit_price]" min="0" step="0.01"
                         class="form-control" value="{{ $item['unit_price'] }}" required>
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
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('offers.index') }}" class="btn btn-secondary me-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Update Offer</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  const productOptions = `@foreach($products as $prod)
    <option value="{{ $prod['id'] }}">{{ $prod['product_name'] }}</option>
  @endforeach`;

  document.getElementById('add-item').addEventListener('click', () => {
    const container = document.getElementById('items-container');
    const idx       = container.querySelectorAll('.item-row').length;

    container.insertAdjacentHTML('beforeend', `
      <div class="row mb-2 item-row">
        <div class="col-md-5">
          <select name="items[${idx}][product_id]" class="form-control" required>
            <option value="">-- Select Product --</option>
            ${productOptions}
          </select>
        </div>
        <div class="col-md-3">
          <input type="number" name="items[${idx}][amount]" min="1"
                 class="form-control" required>
        </div>
        <div class="col-md-3">
          <input type="number" name="items[${idx}][unit_price]" min="0" step="0.01"
                 class="form-control" required>
        </div>
        <div class="col-md-1">
          <button type="button" class="btn btn-danger remove-item">&times;</button>
        </div>
      </div>`);
  });

  document.addEventListener('click', e => {
    if (e.target.matches('.remove-item')) {
      e.target.closest('.item-row').remove();
    }
  });
</script>
@endpush
