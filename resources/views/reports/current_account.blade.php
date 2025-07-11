@extends('layouts.app')
@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header"><h3 class="card-title">Cari Hesap Özeti</h3></div>

   <div class="card-body p-0">
    <div class="table-responsive">
     <table class="table table-hover mb-0">
       <thead>
         <tr>
           <th>Hesap ID</th>
           <th>Müşteri</th>
           <th>Bakiye</th>
           <th>Açılış Tarihi</th>
         </tr>
       </thead>
       <tbody>
         @forelse($accounts as $a)
           <tr>
             <td>{{ $a->id }}</td>
             <td>{{ $a->customer?->customer_name }}</td>
             <td>{{ number_format($a->balance,2) }}</td>
             <td>{{ $a->opening_date }}</td>
           </tr>
         @empty
           <tr><td colspan="4" class="text-center p-4">Veri bulunamadı</td></tr>
         @endforelse
       </tbody>
     </table>
    </div>
   </div>
  </div>
 </div>
</section>
@endsection
