@extends('layouts.app')
@section('content')
<section class="content"><div class="container-fluid">
  <div class="card card-outline card-primary">
    <div class="card-header"><h3 class="card-title">Seri Numarası Ekle</h3></div>
    <form action="{{ route('product_serials.store') }}" method="POST">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="product_id">Ürün *</label>
          <select name="product_id" id="product_id" class="form-control" required>
            <option value="">-- Seçiniz --</option>
            @foreach($products as $p)
              <option value="{{ $p->id }}">{{ $p->product_name }}</option>
            @endforeach
          </select>
        </div>

        <hr>
        <h5>1) Toplu Ekle (her satıra 1 seri numarası)</h5>
        <div class="form-group">
          <textarea name="serials_bulk" class="form-control" rows="5"
                    placeholder="Örnek:
SN001
SN002
SN003…"></textarea>
        </div>

        <hr>
        <h5>2) Dinamik Tek Tek Ekle</h5>
        <div id="dynamic-serials">
          <div class="form-row mb-2">
            <div class="col">
              <input type="text" name="serials[]" class="form-control"
                     placeholder="Seri No">
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-danger remove-row">×</button>
            </div>
          </div>
        </div>
        <button type="button" id="add-serial" class="btn btn-secondary mb-3">
          + Satır Ekle
        </button>
      </div>
      <div class="card-footer text-right">
        <a href="{{ route('product_serials.index') }}"
           class="btn btn-light mr-2">İptal</a>
        <button type="submit" class="btn btn-primary">Kaydet</button>
      </div>
    </form>
  </div>
</div></section>

@push('scripts')
<script>
  document.getElementById('add-serial').onclick = () => {
    let container = document.getElementById('dynamic-serials');
    let row = document.createElement('div');
    row.classList = 'form-row mb-2';
    row.innerHTML = `
      <div class="col">
        <input type="text" name="serials[]" class="form-control" placeholder="Seri No">
      </div>
      <div class="col-auto">
        <button type="button" class="btn btn-danger remove-row">×</button>
      </div>`;
    container.append(row);
  };
  document.getElementById('dynamic-serials').addEventListener('click', e => {
    if (e.target.matches('.remove-row')) {
      e.target.closest('.form-row').remove();
    }
  });
</script>
@endpush
@endsection
