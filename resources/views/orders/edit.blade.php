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
        {{-- Gizli alanlar --}}
        <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">
        <input type="hidden" name="company_id" value="{{ $order->company_id }}">
        <input type="hidden" name="total_amount" id="total_amount" value="{{ $order->total_amount }}">

        <div class="card-body">
          @if($order->company?->company_name)
          <div class="form-group">
            <label>Firma</label>
            <input type="text" class="form-control" value="{{ $order->company->company_name }}" readonly>
          </div>
          @endif

          {{-- Sipariş Türü --}}
          <div class="form-group">
            <label>Sipariş Türü *</label><br>
            <label class="mr-3">
              <input type="radio" name="order_type" value="sale" {{ old('order_type', $order->order_type)=='sale' ? 'checked' : '' }}> Satış
            </label>
            <label>
              <input type="radio" name="order_type" value="purchase" {{ old('order_type', $order->order_type)=='purchase' ? 'checked' : '' }}> Satın Alma
            </label>
            @error('order_type') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          {{-- Tarihler --}}
          <div class="form-group">
            <label for="order_date">Sipariş Tarihi</label>
            <input type="date" id="order_date" name="order_date" required
                   class="form-control @error('order_date') is-invalid @enderror"
                   value="{{ old('order_date', $order->order_date?->format('Y-m-d')) }}">
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

          {{-- Sipariş Kalemleri --}}
          <h5>Sipariş Kalemleri</h5>
          <div id="items-container">
            @php
              $oldItems = old('items', $order->products->map(fn($p)=>[
                'product_id'=>$p->id,
                'amount'=>$p->pivot->amount,
                'unit_price'=>$p->pivot->unit_price,
              ])->toArray());
            @endphp

            @foreach($oldItems as $i=>$item)
            <div class="row mb-2 item-row">
              <div class="col-md-5">
                <select name="items[{{ $i }}][product_id]" class="form-control product-select" required>
                  <option value="">-- Ürün Seçiniz --</option>
                  @foreach($products as $prod)
                    <option value="{{ $prod['id'] }}" data-price="{{ $prod['unit_price'] }}" data-stock="{{ $prod['stock'] }}" {{ $item['product_id']==$prod['id'] ? 'selected' : '' }}>
                      {{ $prod['product_name'] }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <input type="number" name="items[{{ $i }}][amount]" class="form-control amount" min="1" value="{{ $item['amount'] }}" required>
                <small class="text-muted stock-info"></small>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control-plaintext unit-price-view" readonly value="{{ number_format($item['unit_price'],2) }}">
                <input type="hidden" name="items[{{ $i }}][unit_price]" class="unit-price-hidden" value="{{ $item['unit_price'] }}">
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-item">&times;</button>
              </div>
            </div>
            @endforeach
          </div>
          <button type="button" id="add-item" class="btn btn-sm btn-outline-primary">+ Kalem Ekle</button>

          {{-- Ödeme Durumu --}}
          <div class="form-group mt-3">
            <label>
              <input type="checkbox" name="is_paid" value="1" {{ old('is_paid', $order->is_paid) ? 'checked' : '' }}> Ödeme tamamlandı
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
$(function(){
  const products=@json($products);
  let rowIndex=$('#items-container .item-row').length;
  function isSale(){return $('input[name="order_type"]:checked').val()==='sale'}
  function optionList(){return products.map(p=>`<option value="${p.id}" data-price="${p.unit_price}" data-stock="${p.stock}">${p.product_name}</option>`).join('')}
  function clearRow(row){row.find('.unit-price-view').val('0.00');row.find('.unit-price-hidden').val(0);row.find('.amount').val(1).removeAttr('max data-stock');row.find('.stock-info').text('');}
  function refreshDisabled(){const sel=$('.product-select').map((i,e)=>$(e).val()).get();$('.product-select').each(function(){const cur=$(this).val();$(this).find('option').each(function(){const v=$(this).val();if(!v)return;$(this).prop('disabled',sel.includes(v)&&v!==cur);});});}
  function recalc(){let tot=0;$('#items-container .item-row').each(function(){const q=parseFloat($(this).find('.amount').val())||0;const pr=parseFloat($(this).find('.unit-price-hidden').val())||0;tot+=q*pr;});$('#total_amount').val(tot.toFixed(2));}
  function toggleLimit(row){const st=parseInt(row.find('.amount').data('stock')||0,10);if(isSale()&&st){row.find('.amount').attr('max',st);if(+row.find('.amount').val()>st)row.find('.amount').val(st);}else{row.find('.amount').removeAttr('max');}}
  function populate(row){const sel=row.find('.product-select');const v=sel.val();if(!v)return;const opt=sel.find('option:selected')[0];const price=parseFloat(opt.dataset.price||0);const st=parseInt(opt.dataset.stock||0,10);row.find('.unit-price-view').val(price.toFixed(2));row.find('.unit-price-hidden').val(price);row.find('.amount').attr('data-stock',st);row.find('.stock-info').text(`Stok: ${st}`);toggleLimit(row);recalc();}
  $('#add-item').on('click',function(){ $('#items-container').append(`<div class="row mb-2 item-row"><div class="col-md-5"><select name="items[${rowIndex}][product_id]" class="form-control product-select" required><option value="">-- Ürün Seçiniz --</option>${optionList()}</select></div><div class="col-md-3"><input type="number" name="items[${rowIndex}][amount]" class="form-control amount" min="1" value="1" required><small class="text-muted stock-info"></small></div><div class="col-md-3"><input type="text" class="form-control-plaintext unit-price-view" readonly value="0.00"><input type="hidden" name="items[${rowIndex}][unit_price]" class="unit-price-hidden" value="0"></div><div class="col-md-1"><button type="button" class="btn btn-danger remove-item">&times;</button></div></div>`);
    populate($('#items-container .item-row').last());rowIndex++;refreshDisabled();});
  $(document).on('click','.remove-item',function(){$(this).closest('.item-row').remove();refreshDisabled();recalc();});
  $(document).on('change','.product-select',function(){const row=$(this).closest('.item-row');const v=this.value;const dup=$('.product-select').not(this).filter((i,e)=>$(e).val()===v).length;if(dup){alert('Bu ürün zaten eklendi');$(this).val('');clearRow(row);refreshDisabled();recalc();return;}populate(row);refreshDisabled();});
  $(document).on('input','.amount',function(){toggleLimit($(this).closest('.item-row'));recalc();});
  $('#items-container .item-row').each(function(){populate($(this));});refreshDisabled();});
</script>
@endpush
