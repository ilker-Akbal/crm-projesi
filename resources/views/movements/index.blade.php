@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Account Movements</h3>
        <a href="{{ route('movements.create') }}" class="btn btn-sm btn-primary">Add Movement</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Account</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Type</th>
              <th>Explanation</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($movements as $m)
            <tr>
              <td>{{ $m->id }}</td>
              <td>{{ $m->currentCard->customer->customer_name }} ({{ $m->currentCard->id }})</td>
              <td>{{ $m->departure_date }}</td>
              <td>{{ number_format($m->amount,2) }}</td>
              <td>{{ $m->movement_type }}</td>
              <td>{{ $m->explanation }}</td>
              <td>
                <a href="{{ route('movements.show',$m) }}"  class="btn btn-sm btn-info">View</a>
                <a href="{{ route('movements.edit',$m) }}"  class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('movements.destroy',$m) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">No movements found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
