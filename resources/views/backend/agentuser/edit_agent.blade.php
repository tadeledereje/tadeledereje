@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> 
	<div class="page-content">
        <div class="row profile-body">
          <!-- left wrapper start -->
         
          <!-- left wrapper end -->
          <!-- middle wrapper start -->
          <div class="col-md-12 col-xl-12 middle-wrapper">
            <div class="row">
             <div class="card">
              <div class="card-body">
								<h6 class="card-title">Edit Agent</h6>
								<form id="myForm" method="POST" action="{{route('update.agent')}}" class="forms-sample">
									@csrf
                                    <input type="hidden" name="id" value="{{$EditAgent->id}}">
									<div class="form-group">
										<label for="exampleInputUsername1">Agent Name</label>
                                         <input type="text" name="name" class="form-control" value="{{$EditAgent->name}}">
                   
									</div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Agent Email</label>
                                        <input type="text" name="email" class="form-control" value="{{$EditAgent->email}}">
                   
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Agent Phone</label>
                                    <input type="text" name="name" class="form-control" value="{{$EditAgent->phone}}">
                   
                                    </div>

                                      <div class="form-group">
                                        <label for="exampleInputUsername1">Agent Address</label>
                                        <input type="text" name="name" class="form-control" value="{{$EditAgent->address}}">

                  
                 
													
									<div class="form-check form-check-flat form-check-primary">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input">
											Remember me
										<i class="input-frame"></i></label>
									</div>
									<button type="submit" class="btn btn-primary mr-2">Save Change</button>
									<button class="btn btn-light">Cancel</button>
								</form>
              </div>
            </div>

            </div>
          </div>
          <!-- middle wrapper end -->
          <!-- right wrapper start -->
          
          <!-- right wrapper end -->
          <script type="text/javascript">
            $(document).ready(function (){
                $('#myForm').validate({
                    rules: {
                        amenitis_name: {
                            required : true,
                        }, 
                        
                    },
                    messages :{
                        amenitis_name: {
                            required : 'Please Enter AmenitiesName',
                        }, 
                         
        
                    },
                    errorElement : 'span', 
                    errorPlacement: function (error,element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight : function(element, errorClass, validClass){
                        $(element).addClass('is-invalid');
                    },
                    unhighlight : function(element, errorClass, validClass){
                        $(element).removeClass('is-invalid');
                    },
                });
            });
            
        </script>
        </div>

			</div>
@endsection