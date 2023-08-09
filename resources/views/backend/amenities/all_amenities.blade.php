@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
  <a href="{{route('add.Amenitie')}}">Add Amenities</a> 
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
<div class="card">
  <div class="card-body">
    <h6 class="card-title">Amenities All</h6>
    
    <div class="table-responsive">
      <table id="dataTableExample" class="table">
        <thead>
          <tr>
            <th>S1</th>
            <th>Amenities name</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($amenities as $key=>$item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->amenitis_name}}</td>
                <td>
        <a href="{{route('edit.eminitie',$item->id)}}" class="btn btn-warning">Edit</a>
        <a href="{{route('delete.eminitie',$item->id)}}" class="btn btn-inverse-danger" id="delete">Delete</a>
                </td>
              </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
        </div>
    </div>

</div>




@endsection