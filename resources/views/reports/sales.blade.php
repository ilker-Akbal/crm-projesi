@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Sales Report</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($sales as $s)
                <tr>
                  <td>{{ $s->id }}</td>
                  <td>{{ $s->customer->customer_name }}</td>
                  <td>{{ $s->order_date }}</td>
                  <td>{{ number_format($s->total_amount, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center p-4">No data found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
