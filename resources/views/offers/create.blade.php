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
                      {{ old('company_id') == $c->id ? 'selected' : '' }}>
                      {{ $c->company_name }}
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

            {{-- Durum (sadece hazırlaniyor dropdown) --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="status">Durum</label>
                <select name="status" id="status" class="form-control" required>
                  <option value="hazırlanıyor" selected>Hazırlanıyor</option>
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
                  <select name="items[{{ $i }}][product_id]" class="form-control product-select" required>
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
                  <input type="number" name="items[{{ $i }}][amount]" min="1" class="form-control amount"
                         value="{{ $item['amount'] ?? 1 }}" placeholder="Adet" required>
                  <small class="text-muted stock-info"></small>
                </div>
                <div class="col-md-3">
                  <input type="text" class="form-control-plaintext unit-price-view" value="0.00" readonly>
                  <input type="hidden" name="items[{{ $i }}][unit_price]"
                         class="unit-price-hidden" value="{{ $item['unit_price'] ?? 0 }}">
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

  const products = @json($products);              // [{id,product_name,unit_price,stock}, …]
  let   rowIndex = $('#items-container .item-row').length;

  /* ---------- yardımcılar ---------- */
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

  /* ---------- satır ekle ---------- */
  $('#add-row, #add-item, #add-row').on('click', ()=>{      // id adı değişti
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

  /* ---------- satır sil ---------- */
  $(document).on('click','.remove-item',function(){
    $(this).closest('.item-row').remove();
    refreshDisabledOptions();
    recalcTotals();
  });

  /* ---------- ürün seçimi ---------- */
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

  /* ---------- miktar değişimi ---------- */
  $(document).on('input','.amount',function(){
    const max=parseInt($(this).attr('max')||0,10);
    if(max&&+this.value>max) this.value=max;
    recalcTotals();
  });

  /* ilk yüklemede */
  refreshDisabledOptions();
  recalcTotals();

});
</script>
@endpush
