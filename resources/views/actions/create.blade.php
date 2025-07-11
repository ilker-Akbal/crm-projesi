@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Yeni İşlem Oluştur</h3></div>

      <div class="card-body">
        {{-- Demo form – henüz veri kaydetmez --}}
        <form>
          <div class="form-group">
            <label>Müşteri</label>
            <select class="form-control">
              <option value="">Seçiniz…</option>
            </select>
          </div>

          <div class="form-group">
            <label>İşlem Türü</label>
            <input type="text" class="form-control" placeholder="Örn: Telefon Görüşmesi">
          </div>

          <div class="form-group">
            <label>İşlem Tarihi</label>
            <input type="date" class="form-control">
          </div>

          <button type="submit" class="btn btn-primary">Kaydet (demo)</button>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
