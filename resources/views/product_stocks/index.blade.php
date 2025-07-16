@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Product Stocks</h3>
       
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Update Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($productStocks as $s)
            <tr>
              <td>{{ $s->id }}</td>
              <td>{{ $s->product->product_name }}</td>
              <td>{{ $s->stock_quantity }}</td>
              <td>{{ $s->update_date }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">No stock records.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
