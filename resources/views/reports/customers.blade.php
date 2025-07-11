@extends('layouts.app')
@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header"><h3 class="card-title">Müşteri Raporu</h3></div>

   <div class="card-body p-0">
    <div class="table-responsive">
     <table class="table table-hover mb-0">
       <thead>
         <tr>
           <th>ID</th>
           <th>Adı</th>
           <th>Türü</th>
           <th>Telefon</th>
           <th>E-posta</th>
         </tr>
       </thead>
       <tbody>
         @forelse($customers as $c)
           <tr>
             <td>{{ $c->id }}</td>
             <td>{{ $c->customer_name }}</td>
             <td>{{ $c->customer_type }}</td>
             <td>{{ $c->phone }}</td>
             <td>{{ $c->email }}</td>
           </tr>
         @empty
           <tr><td colspan="5" class="text-center p-4">Veri bulunamadı</td></tr>
         @endforelse
       </tbody>
     </table>
    </div>
   </div>
  </div>
 </div>
</section>
@endsection
