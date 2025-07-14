@extends('layouts.app')

@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-outline card-primary">
   <div class="card-header"><h3 class="card-title">Add Order</h3></div>
   <form action="{{ route('orders.store') }}" method="POST">
    @csrf
    <div class="card-body">
     @include('partials.alerts')

     <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="customer_id">Customer</label>
          <select name="customer_id" id="customer_id" class="form-control" required>
            <option value="">-- select --</option>
            @foreach($customers as $c)
              <option value="{{ $c->id }}" {{ old('customer_id')==$c->id?'selected':'' }}>
                {{ $c->customer_name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="Order_Date">Order Date</label>
          <input type="date" name="Order_Date" id="Order_Date" class="form-control"
            value="{{ old('Order_Date',today()->toDateString()) }}" required>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="Delivery_date">Delivery Date</label>
          <input type="date" name="Delivery_date" id="Delivery_date" class="form-control"
            value="{{ old('Delivery_date') }}">
        </div>
      </div>
     </div>

     <h5>Order Items</h5>
     <div class="table-responsive">
      <table class="table table-bordered" id="order-items">
        <thead>...
        <!-- aynısını önceki JS’li snippet ile kullanabilirsiniz -->
        </thead>
        <tbody>...</tbody>
      </table>
      <button type="button" class="btn btn-sm btn-secondary" id="add-row">Add Row</button>
     </div>

     <div class="d-flex justify-content-end mt-3">
       <h4>Total: <span id="order-total">0.00</span></h4>
       <input type="hidden" name="total_amount" id="total_amount" value="0">
     </div>
    </div>
    <div class="card-footer">
      @include('partials.form-buttons')
    </div>
   </form>
  </div>
 </div>
</section>
@endsection

@push('scripts')
<script>/* JS ile subtotal ve total hesaplama (bir önceki mesajdakini kullanın) */</script>
@endpush
