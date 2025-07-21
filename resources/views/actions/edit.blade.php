{{-- resources/views/actions/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    {{-- Başarılı/hatali bildirimler --}}
    @include('partials.alerts')

    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
          <i class="fas fa-edit mr-1"></i> İşlem Düzenle
        </h3>
        <a href="{{ route('actions.index') }}" class="btn btn-sm btn-secondary">
          <i class="fas fa-arrow-left"></i> Listeye Dön
        </a>
      </div>

      <form action="{{ route('actions.update', $action) }}" method="POST" class="form-horizontal">
        @csrf @method('PUT')

        <div class="card-body">
          <div class="row">
            {{-- İşlem Türü --}}
            <div class="col-md-6">
              <div class="form-group">
                <label for="action_type">İşlem Türü <span class="text-danger">*</span></label>
                <input  id="action_type"
                        name="action_type"
                        type="text"
                        class="form-control @error('action_type') is-invalid @enderror"
                        value="{{ old('action_type', $action->action_type) }}"
                        placeholder="Örn: Telefon Görüşmesi"
                        required>
                @error('action_type')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- İşlem Tarihi --}}
            <div class="col-md-6">
              <div class="form-group">
                <label for="action_date">Tarih <span class="text-danger">*</span></label>
                <input  id="action_date"
                        name="action_date"
                        type="date"
                        class="form-control @error('action_date') is-invalid @enderror"
                        value="{{ old('action_date', $action->action_date->format('Y-m-d')) }}"
                        required>
                @error('action_date')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div><!-- /.row -->
        </div><!-- /.card-body -->

        <div class="card-footer text-right">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Güncelle
          </button>
        </div>
      </form>
    </div><!-- /.card -->
  </div><!-- /.container-fluid -->
</section>
@endsection