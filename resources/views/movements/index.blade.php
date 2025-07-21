@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    <div class="card card-outline card-primary">
      {{-- Başlık + Yeni Kayıt --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Account Movements</h3>
        <a href="{{ route('movements.create') }}" class="btn btn-sm btn-primary">Add Movement</a>
      </div>

      {{-- ---- Filtre Formu ---- --}}
      <form method="GET" class="p-3 border-bottom">
        <div class="row g-2">
          <div class="col-sm">
            <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="From">
          </div>
          <div class="col-sm">
            <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="To">
          </div>
          <div class="col-sm">
            <select name="type" class="form-select">
              <option value="">All Types</option>
              <option value="Debit"  @selected(request('type')=='Debit') >Debit</option>
              <option value="Credit" @selected(request('type')=='Credit')>Credit</option>
            </select>
          </div>
          <div class="col-sm">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search explanation">
          </div>
          <div class="col-auto">
            <button class="btn btn-primary">Filter</button>
          </div>
        </div>
      </form>

      {{-- ---- Tablo ---- --}}
      <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle text-nowrap">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Account</th>
              <th>Date</th>
              <th class="text-end">Debit</th>
              <th class="text-end">Credit</th>
              <th class="text-end">Running&nbsp;Bal.</th>
              <th>Explanation</th>
              <th style="width:140px">Actions</th>
            </tr>
          </thead>
          <tbody>
            @php $running = 0; @endphp

            @forelse($movements as $m)
              @php
                $delta   = $m->movement_type === 'Debit' ? -$m->amount : $m->amount;
                $running+= $delta;
              @endphp
              <tr>
                <td>{{ $m->id }}</td>
                <td>{{ $m->currentCard->customer->customer_name }} ({{ $m->current_id }})</td>
                <td>{{ \Carbon\Carbon::parse($m->departure_date)->format('d.m.Y') }}</td>

                {{-- Debit / Credit renkli hücreler --}}
                <td class="text-end text-danger">
                  {{ $m->movement_type=='Debit' ? number_format($m->amount,2) : '' }}
                </td>
                <td class="text-end text-success">
                  {{ $m->movement_type=='Credit' ? number_format($m->amount,2) : '' }}
                </td>

                <td class="text-end fw-semibold">{{ number_format($running,2) }}</td>
                <td>{{ $m->explanation }}</td>

                <td>
                  <a href="{{ route('movements.show',$m) }}" class="btn btn-xs btn-info">View</a>
                  <a href="{{ route('movements.edit',$m) }}" class="btn btn-xs btn-warning">Edit</a>
                  <form action="{{ route('movements.destroy',$m) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete?')" class="btn btn-xs btn-danger">Del</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="8" class="text-center">No movements found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Sayfalama (paginate kullanıyorsanız) --}}
      @if(method_exists($movements,'links'))
        <div class="card-footer">{{ $movements->links() }}</div>
      @endif
    </div>
  </div>
</section>
@endsection
