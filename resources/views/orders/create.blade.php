@extends('layouts.app')
@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header"><h3 class="card-title">Add Order</h3></div>
   <form action="{{ route('orders.store') }}" method="POST">
    @csrf
    <div class="card-body">
     @include('partials.alerts')
     <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="customer_id">Customer</label>
          <select name="customer_id" id="customer_id" class="form-control" required>
            <option value="">-- select --</option>
            @foreach($customers as $c)
              <option value="{{ $c->id }}" {{ old('customer_id')==$c->id ? 'selected' : '' }}>{{ $c->customer_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="order_date">Order Date</label>
          <input type="date" name="order_date" id="order_date" class="form-control" required value="{{ old('order_date', today()->toDateString()) }}">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="delivery_date">Delivery Date</label>
          <input type="date" name="delivery_date" id="delivery_date" class="form-control" value="{{ old('delivery_date') }}">
        </div>
      </div>
     </div>

     {{-- Products table --}}
     <div class="table-responsive">
      <table class="table table-bordered" id="order-items">
        <thead>
          <tr>
            <th style="width:30%">Product</th>
            <th style="width:15%">Qty</th>
            <th style="width:20%">Unit Price</th>
            <th style="width:20%">Subtotal</th>
            <th style="width:15%"></th>
          </tr>
        </thead>
        <tbody>
          <!-- JS will clone this row -->
          <tr>
            <td>
              <select name="items[0][product_id]" class="form-control select-product">
                <option value="">-- select product --</option>
                @foreach($products as $p)
                  <option value="{{ $p->id }}" data-price="{{ $p->price?->price ?? 0 }}">{{ $p->product_name }}</option>
                @endforeach
              </select>
            </td>
            <td><input type="number" name="items[0][amount]" class="form-control qty" min="1" value="1"></td>
            <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control unit-price"></td>
            <td class="subtotal text-right align-middle">0.00</td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
          </tr>
        </tbody>
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
<script>
(function(){
 const tbody = document.querySelector('#order-items tbody');
 let rowIdx = 0;

 function recalc(){
   let total = 0;
   tbody.querySelectorAll('tr').forEach(tr=>{
     const qty = parseFloat(tr.querySelector('.qty').value)||0;
     const price = parseFloat(tr.querySelector('.unit-price').value)||0;
     const sub = qty*price;
     tr.querySelector('.subtotal').textContent = sub.toFixed(2);
     total += sub;
   });
   document.querySelector('#order-total').textContent = total.toFixed(2);
   document.querySelector('#total_amount').value = total.toFixed(2);
 }

 tbody.addEventListener('input', recalc);
 tbody.addEventListener('click', e=>{
   if(e.target.classList.contains('remove-row')){
     e.target.closest('tr').remove();
     recalc();
   }
 });

 document.getElementById('add-row').addEventListener('click', () => {
   rowIdx++;
   const template = tbody.querySelector('tr');
   const clone = template.cloneNode(true);
   clone.querySelectorAll('select, input').forEach(el=>{
     if(el.name.includes('items[')){
       el.name = el.name.replace(/items\[\d+\]/, `items[${rowIdx}]`);
     }
     if(el.classList.contains('qty')) el.value = 1;
     if(el.classList.contains('unit-price')) el.value = '';
   });
   clone.querySelector('.subtotal').textContent = '0.00';
   tbody.appendChild(clone);
 });
})();
</script>
@endpush
