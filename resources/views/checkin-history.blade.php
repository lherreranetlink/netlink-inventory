@extends('layout.master')

@section('title')
Log: Recent Checkins
@endsection



@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>

<div class="row " style="margin-top:2px;" >
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="{{ Request::path() == 'checkins' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/checkins')}}" ><i class="fa fa-list-alt" ></i> Recent Checkins</a>
                    | <a class="{{ Request::path() == 'checkouts' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/checkouts')}}" ><i class="fa fa-list-alt" ></i> Recent Checkouts</a>
                    | <a class="{{ Request::path() == 'location-changes' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('//location-changes')}}" ><i class="fa fa-map-marker" ></i> Recent Location Change</a>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Model</th>
                            <th>Checkin By</th>
                            <th>MAC/Indetifier</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                <?php 
                    foreach($data['checkins'] as $checkin){
                ?>
                    <tr>
                        <td>{{date("M d Y h:i A",strtotime($checkin->created_at))}}</td>
                        <td>{{$checkin->model->manufacturer->name}} {{$checkin->model->model_number}} | {{$checkin->model->category->name}}</td>
                        <td>{{$checkin->user->name}}</td>
                        <td><?php if(isset($checkin->inout->mac)){echo $checkin->inout->mac;} ?> | <?php if(isset($checkin->inout->identifier)){echo $checkin->inout->identifier;} ?></td>
                        
                    </tr>
                <?php 
                    }
                ?>
                    </tbody>
                </table>
                {{ $data['checkins']->links() }}
            </div>
        </div>
    </div>
</div>    
<!-- InputMask -->
<script src="{{URL::to('vendors/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{URL::to('vendors/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{URL::to('vendors/input-mask/jquery.inputmask.extensions.js')}}"></script> 
<script src="{{URL::to('vendors/input-mask/jquery.inputmask.bundle.min.js')}}"></script> 
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script> 

<script type="text/javascript" src="{{url('vendors/datatables/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{url('vendors/datatables/js/dataTables.bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('vendors/datatables/js/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{url('vendors/datatables/js/buttons.print.min.js')}}"></script>
<script type="text/javascript" src="{{url('vendors/datatables/js/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="{{url('vendors/datatables/js/buttons.bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('vendors/datatables/js/dataTables.responsive.js')}}"></script>

<script>
$(document).ready(function(){
    $('#sample_1').dataTable({
            "pageLength": 15,
            "lengthMenu": [15, 25, 50, 100]
        });
    $("[data-mask]").inputmask();
    $("#did").inputmask({"mask": "(999) 999-9999"});
    $(".select2").select2();
    
    
  });
</script>
@endsection