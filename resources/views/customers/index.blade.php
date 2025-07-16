@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Customers</h2>

    <div class="mb-3">
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Add New Customer</a>
    </div>

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Email</th>
                <th scope="col" style="width: 200px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <th scope="row">{{ $customer->id }}</th>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ ucfirst($customer->customer_type) }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="btn btn-sm btn-danger" 
                                onclick="return confirm('Delete this customer?')"
                            >
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
