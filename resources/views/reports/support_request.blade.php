@extends('layouts.app')
@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header"><h3 class="card-title">Destek Talep Raporu</h3></div>

   <div class="card-body p-0">
    <div class="table-responsive">
     <table class="table table-hover mb-0">
       <thead>
         <tr>
           <th>ID</th>
           <th>Müşteri</th>
           <th>Başlık</th>
           <th>Durum</th>
           <th>Kayıt Tarihi</th>
         </tr>
       </thead>
       <tbody>
         @forelse($requests as $r)
           <tr>
             <td>{{ $r->id }}</td>
             <td>{{ $r->customer?->customer_name }}</td>
             <td>{{ $r->title }}</td>
             <td>{{ $r->situation }}</td>
             <td>{{ $r->registration_date }}</td>
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
