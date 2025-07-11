@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Orders</h3>
                <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">Add Order</a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="orders-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Order Date</th>
                                <th>Delivery Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($orders as $o)
                                <tr>
                                    <td>{{ $o->id }}</td>
                                    <td>{{ $o->customer?->customer_name }}</td>
                                    <td>{{ $o->order_date }}</td>
                                    <td>{{ $o->delivery_date }}</td>
                                    <td>{{ number_format($o->total_amount, 2) }}</td>
                                    <td>{{ $o->situation }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-info">Items</a>
                                        <a href="{{ route('orders.edit', $o) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('orders.destroy', $o) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No records found.</td>
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
