@extends('layouts.admin')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Issue Product Details</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="{{route('home')}}"><i class="fa fa-home"></i> Home</a>
            </li>
            @for($i = 2; $i <= count(Request::segments()); $i++)
               <li class="breadcrumb-item active">
                  <a href="{{ URL::to( implode( '/', array_slice(Request::segments(), 0 ,$i, true)))}}">
                     {{ucwords(Request::segment($i))}}
                  </a>
               </li>
            @endfor
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title pb-2">Issue Product Details</h3>

      </div>
      <div class="card-body">

        <table class="table table-sm table-striped table-bordered table-hover data-table">
          <thead>
            <tr>
              <th width="100px">#</th>
                <th>Product Type</th>
                <th>Product Name</th>
                <th>HSN Code</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
               @if(count($issue)>0)
            @php $id=0; @endphp
            @foreach($issue as $issues)
            @php $id++; @endphp
            </tr>
            <tr>
              <td>{{$id}}</td>
                  <td>{{$issues->item_category_name}}</td>
                  <td>{{$issues->item_name}}</td>
                  <td>{{$issues->hsn}}</td>
                  <td>{{$issues->quantity}}</td>
                  <td>{{$issues->price}}</td>
                  <td>{{$issues->total_amount}}</td>
                  </tr>
                   @endforeach
                    @endif
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>
    </div>
  </section>
  </div> 
@endsection
