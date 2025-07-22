@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Orders</h3>
        <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">Add Order</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Order Date</th>
              <th>Delivery Date</th>
               <th>payment status</th>
               <th>Total</th>
              <th>Actions</th>
             
              
            </tr>
          </thead>
          <tbody>
            @forelse($orders as $o)
            <tr>
              <td>{{ $o->id }}</td>
              <td>{{ $o->customer->customer_name }}</td>
              <td>{{ $o->order_date }}</td>
              <td>{{ $o->delivery_date }}</td>
              <td>
  {{ $o->is_paid
        ? '✓ Ödendi ('.optional($o->paid_at)->format('d.m.Y').')'
        : 'Bekliyor' }}
</td>
              <td>{{ number_format($o->total_amount,2) }}</td>
              <td>
                <a href="{{ route('orders.show',$o) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('orders.edit',$o) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('orders.destroy',$o) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">No orders found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
