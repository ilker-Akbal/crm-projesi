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
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          <div class="row">
            {{-- Şirket --}}
            <div class="col-md-3">
              <div class="form-group">
                <label for="company_id">Şirket (opsiyonel)</label>
                <select name="company_id" id="company_id" class="form-control @error('company_id') is-invalid @enderror">
                  <option value="">-- seçiniz --</option>
                  @foreach($companies as $c)
                    <option value="{{ $c->id }}" @selected(old('company_id')==$c->id)>{{ $c->company_name }}</option>
                  @endforeach
                </select>
                @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Teklif Tarihi --}}
            <div class="col-md-3">
              <div class="form-group">
                <label for="offer_date">Teklif Tarihi</label>
                <input type="date" name="offer_date" id="offer_date"
                       class="form-control @error('offer_date') is-invalid @enderror"
                       value="{{ old('offer_date', today()->toDateString()) }}" required>
                @error('offer_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Teslimat Tarihi (YENİ) --}}
            <div class="col-md-3">
              <div class="form-group">
                <label for="delivery_date">Teslimat Tarihi</label>
                <input type="date" name="delivery_date" id="delivery_date"
                       class="form-control @error('delivery_date') is-invalid @enderror"
                       value="{{ old('delivery_date') }}">
                @error('delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Geçerlilik Tarihi --}}
            <div class="col-md-3">
              <div class="form-group">
                <label for="valid_until">Geçerlilik Tarihi *</label>
                <input type="date" name="valid_until" id="valid_until"
                       class="form-control @error('valid_until') is-invalid @enderror"
                       value="{{ old('valid_until') }}" required>
                @error('valid_until') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          {{-- Durum --}}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="status">Durum</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                  <option value="hazırlanıyor" {{ old('status','hazırlanıyor')==='hazırlanıyor'?'selected':'' }}>Hazırlanıyor</option>
                  <option value="gönderildi"  {{ old('status')==='gönderildi'?'selected':'' }}>Gönderildi</option>
                  <option value="kabul"       {{ old('status')==='kabul'?'selected':'' }}>Kabul</option>
                  <option value="reddedildi"  {{ old('status')==='reddedildi'?'selected':'' }}>Reddedildi</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          {{-- Kalemler --}}
          @php $oldItems = old('items', []); @endphp
          <hr><h5>Teklif Kalemleri</h5>
          <div id="items-container">
            @foreach($oldItems as $i => $item)
              <div class="row mb-2 item-row">
                <div class="col-md-5">
                  <select name="items[{{ $i }}][product_id]" class="form-control product-select" required>
                    <option value="">-- Ürün Seçiniz --</option>
                    @foreach($products as $prod)
                      <option value="{{ $prod['id'] }}" @selected(($item['product_id']??null)==$prod['id'])>{{ $prod['product_name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <input type="number" name="items[{{ $i }}][amount]" min="1" class="form-control amount" value="{{ $item['amount']??1 }}" required>
                  <small class="text-muted stock-info"></small>
                </div>
                <div class="col-md-3">
                  <input type="text" class="form-control-plaintext unit-price-view" value="0.00" readonly>
                  <input type="hidden" name="items[{{ $i }}][unit_price]" class="unit-price-hidden" value="{{ $item['unit_price']??0 }}">
                </div>
                <div class="col-md-1"><button type="button" class="btn btn-danger remove-item">&times;</button></div>
              </div>
            @endforeach
          </div>
          <button type="button" id="add-item" class="btn btn-sm btn-outline-primary">+ Kalem Ekle</button>

          <div class="d-flex justify-content-end mt-3">
            <h5>Toplam: <span id="offer-total">0.00</span> ₺</h5>
            <input type="hidden" name="total_amount" id="total_amount" value="0">
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
$(function () {

  const products = @json($products);
  let   rowIndex = $('#items-container .item-row').length;

  function optionList() {
    return products.map(p =>
      `<option value="${p.id}" data-price="${p.unit_price}" data-stock="${p.stock}">
         ${p.product_name}
       </option>`).join('');
  }

  function clearRow(row){
    row.find('.unit-price-view').val('0.00');
    row.find('.unit-price-hidden').val(0);
    row.find('.amount').val(1).removeAttr('max');
    row.find('.stock-info').text('');
  }

  function refreshDisabledOptions(){
    const selected = $('.product-select').map((i,e)=> $(e).val()).get();
    $('.product-select').each(function(){
      const cur = $(this).val();
      $(this).find('option').each(function(){
        const v=$(this).val();
        if(!v) return;
        $(this).prop('disabled', selected.includes(v) && v!==cur);
      });
    });
  }

  function recalcTotals(){
    let total = 0;
    $('#items-container .item-row').each(function(){
      const qty = parseFloat($(this).find('.amount').val()) || 0;
      const pr  = parseFloat($(this).find('.unit-price-hidden').val()) || 0;
      total += qty*pr;
    });
    $('#total_amount').val(total.toFixed(2));
    $('#offer-total').text(total.toFixed(2));
  }

  $('#add-item').on('click', ()=>{
    $('#items-container').append(`
      <div class="row mb-2 item-row">
        <div class="col-md-5">
          <select name="items[${rowIndex}][product_id]"
                  class="form-control product-select" required>
            <option value="">-- Ürün Seçiniz --</option>${optionList()}
          </select>
        </div>
        <div class="col-md-3">
          <input type="number" name="items[${rowIndex}][amount]"
                 class="form-control amount" min="1" value="1">
          <small class="text-muted stock-info"></small>
        </div>
        <div class="col-md-3">
          <input type="text"  class="form-control-plaintext unit-price-view" value="0.00" readonly>
          <input type="hidden" name="items[${rowIndex}][unit_price]"
                 class="unit-price-hidden" value="0">
        </div>
        <div class="col-md-1">
          <button type="button" class="btn btn-danger remove-item">&times;</button>
        </div>
      </div>`);
    rowIndex++;
    refreshDisabledOptions();
  });

  $(document).on('click','.remove-item',function(){
    $(this).closest('.item-row').remove();
    refreshDisabledOptions();
    recalcTotals();
  });

  $(document).on('change','.product-select',function(){
    const val=this.value, row=$(this).closest('.item-row');

    if(!val){
      clearRow(row);
      refreshDisabledOptions(); recalcTotals();
      return;
    }

    if($('.product-select').not(this).filter((i,e)=> $(e).val()===val).length){
      alert('Bu ürün zaten eklendi. Aynı ürünü tekrar seçemezsiniz.');
      $(this).val('');
      clearRow(row); refreshDisabledOptions(); recalcTotals();
      return;
    }

    const opt=this.selectedOptions[0],
          price=parseFloat(opt.dataset.price||0),
          stock=parseInt(opt.dataset.stock||0,10);

    row.find('.unit-price-view').val(price.toFixed(2));
    row.find('.unit-price-hidden').val(price);
    row.find('.amount').attr('max',stock).val(1);
    row.find('.stock-info').text(`Stok: ${stock}`);

    refreshDisabledOptions(); recalcTotals();
  });

  $(document).on('input','.amount',function(){
    const max=parseInt($(this).attr('max')||0,10);
    if(max&&+this.value>max) this.value=max;
    recalcTotals();
  });

  refreshDisabledOptions();
  recalcTotals();
});
</script>
@endpush
