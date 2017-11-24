@extends('layout.master')

@section('title')
<u style="font-weight: bold;">Locations</u>
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="vendors/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="vendors/datatables/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/responsive.dataTables.css">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="css/pages/datatables.css">
    
    
    
    
<div class="row ">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-table"></i> Locations | <a style="border-bottom: 1px dashed #FF0000;" href="" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle" ></i> Add New Location</a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <!--
                    <a href="{{url('/sublocations')}}" class="btn btn-primary">Sub Location</a>
                    <a href="{{url('/childlocations')}}" class="btn btn-primary">Child Location</a>
                    -->
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>EDIT</th>
                            <th>DELETE</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($data['locations'] as $location){
                            ?>
                            <tr>
                                <td>
                                <?php 
                                    if($location['deleted']==1){ 
                                        echo "<label class='label label-danger'>Deleted</label>";
                                    }
                                    else{
                                        echo "<label class='label label-success'>Active</label>";
                                    }
                                ?>
                                </td>
                                <td><?php echo $location['name']; ?></td>
                                <td><?php echo $location['description']; ?></td>
                                <td>

                                    <form method="get" action="{{url('/locations/edit')}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$location['id']}}" />
                                        <button type="submit" class="btn btn-sm btn-warning" ><i class="fa fa-edit"></i></button>
                                    </form>

                                    <!-- Modal -->
                                    <div id="deleteLocationConfirm_<?php echo $location['id']; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                        <form class="form-horizontal" method="post" action="{{url('/delete-location')}}">
                                          {{ csrf_field() }}
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Confirm Delete?</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row" >
                                                <div class="col-md-12">
                                                    <h3>Are Your Sure Want to Delete?</h3>
                                                    <h4><?php echo $location['name'] ?></h4>
                                                    <input type="hidden" name="id" value="<?php echo $location['id']; ?>"/>
                                                </div>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-success" >Yes</button>
                                          </div>
                                          </form>
                                        </div>

                                      </div>
                                    </div>


                                </td>
                                <td><button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteLocationConfirm_<?php echo $location['id']; ?>"><i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="{{url('locations/sublocations')}}/{{$location['id']}}" class="btn btn-success btn-sm"><i class="fa fa-list"></i> SubLocations
                                        (<b>{{count($location->sublocations)}}</b>)
                                    </a>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    
    

</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <form style="padding: 20px;" class="form-horizontal" method="post" action="{{url('/locations')}}">
          {{ csrf_field() }}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
          <div class="row" >
            <div class="col-md-12">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required="" />
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" >Submit</button>
      </div>
      </form>
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