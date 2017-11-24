@extends('layout.master')

@section('title')
{{$data['title']}}: Child Locations
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>   
    
    
    
    
<div class="row ">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-table"></i> Child Locations <small>(Locations of Sub Locations)</small> | <a style="border-bottom: 1px dashed #FF0000;" href="" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle" ></i> Add New Child Location</a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row" style="text-align:  center;">
                    @if (count($errors) > 0)
                    <div style="padding: 10px" class="alert alert-danger col-lg-6 col-lg-offset-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="table-responsive">
                    <a href="{{url('/locations')}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Locations</a>
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>NAME</th>
                            <th>PARENT SUBLOCATION</th>
                            <th>EDIT</th>
                            <th>DELETE</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($data['childlocations'] as $childlocation){
                            ?>
                            <tr>
                                <td>
                                    <?php 
                                        if($childlocation['deleted']==1){ 
                                            echo "<label class='label label-danger'>Deleted</label>";
                                        }
                                        else{
                                            echo "<label class='label label-success'>Active</label>";
                                        }
                                    ?>
                                </td>
                                <td><?php echo $childlocation['name']; ?></td>
                                <td><?php echo $childlocation->sublocation->location->name." <i class='fa fa-arrow-right'></i> ".$childlocation->sublocation->name; ?></td>
                                <td>

                                    <form method="get" action="{{url('/childlocations/edit')}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$childlocation['id']}}" />
                                        <button type="submit" class="btn btn-sm btn-warning" ><i class="fa fa-edit"></i></button>
                                    </form>

                                    <!-- Modal -->
                                    <div id="deleteChildCategoryConfirm_<?php echo $childlocation['id']; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                        <form class="form-horizontal" method="post" action="{{url('/delete-childlocation')}}">
                                          {{ csrf_field() }}
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row" >
                                                <div class="col-md-12">
                                                    <h3>Are Your Sure Want to Delete?</h3>
                                                    <h4><?php echo $childlocation['name'] ?></h4>
                                                    <input type="hidden" name="id" value="<?php echo $childlocation['id']; ?>"/>
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
                                <td><button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteChildCategoryConfirm_<?php echo $childlocation['id']; ?>"><i class="glyphicon glyphicon-trash"></i>
                                    </button>
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
        <form style="padding: 20px;" class="form-horizontal" method="post" action="{{url('/childlocations')}}">
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
                    <label>Parent Sub Category</label><br>
                    <select style="width: 170px"  name="sublocation_id" class="select2">
                        <option value="">Select</option>
                        <?php 
                        foreach($data['sublocations'] as $cat){
                        ?>
                        <option value="<?php echo $cat->id; ?>"><?php echo $cat->name." - ".$cat->location->name; ?></option>
                        <?php 
                        }
                        ?>
                        
                    </select>
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