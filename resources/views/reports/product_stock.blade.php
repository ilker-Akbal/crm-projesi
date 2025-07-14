@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Product Stock Report</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Stock Quantity</th>
                <th>Update Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse($stocks as $s)
                <tr>
                  <td>{{ $s->id }}</td>
                  <td>{{ $s->product->product_name }}</td>
                  <td>{{ $s->stock_quantity }}</td>
                  <td>{{ $s->update_date }}</td>
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
