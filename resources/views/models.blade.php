@extends('layout.master')

@section('title')
{{$data['title']}}
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="vendors/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="vendors/datatables/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/responsive.dataTables.css">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="css/pages/datatables.css">
    
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>
    
    
<div class="row ">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-table"></i> Models <small></small> | <a style="border-bottom: 1px dashed #FF0000;" href="{{url('/models/add')}}" ><i class="fa fa-plus-circle" ></i> Add New Model</a>
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
                    Filter<br>
                    <script type="text/javascript">
                        function filterManufacturer(){
                            //alert(document.getElementById("select_box").value);
                            document.getElementById("myForm").submit();
                        }
                        
                    </script>
                    <form name="myForm" id="myForm"  method="post" >
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label>Manufacturer:</label>
                            <select id="select_box" onchange="filterManufacturer()" name="manufacturer_id" style="width: 180px" class="select2">
                                <option value="">Select</option>
                                <?php 
                                foreach($data['manufacturers'] as $manu){
                                ?>
                                <option <?php if($data['selected_manu']==$manu['id']){echo "selected";} ?> value="<?php echo $manu['id']; ?>"><?php echo $manu['name']; ?></option>
                                <?php
                                }
                                        
                                ?>
                            </select>
                        </div>
                    </form>
                    
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>NAME</th>
                            <th>CATEGORY</th>
                            <th>EDIT</th>
                            <th>DELETE</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($data['models'] as $model){
                            ?>
                            <tr>
                                <td>
                                    <?php 
                                        if($model['deleted']==1){ 
                                            echo "<label class='label label-danger'>Deleted</label>";
                                        }
                                        else{
                                            echo "<label class='label label-success'>Active</label>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if($model->picture==""){
                                        $picture = url('/img/authors/avatar1.jpg');
                                    }
                                    else{
                                        $picture = $model->picture;
                                    }
                                    ?>
                                    <img src="{{$picture}}" width="30" />
                                    <?php 
                                        echo $model->manufacturer->name." <b>".$model['model_number']."</b>"; 
                                    ?>
                                </td>
                                <td><?php 
                                    echo $model->category->name; 
                                    if($model->subcategory_id!=0){
                                        echo "<i class='fa fa-arrow-right'></i>".$model->subcategory->name; 
                                    }
                                    if($model->childcategory_id!=0){
                                        echo "<i class='fa fa-arrow-right'></i>".$model->childcategory->name; 
                                    }
                                ?></td>
                                <td>
                                    <form method="post" action="{{url('/models/edit')}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$model['id']}}" />
                                        <button type="submit" class="btn btn-sm btn-warning" ><i class="fa fa-edit"></i></button>
                                    </form>

                                    <!-- Modal -->
                                    <div id="deleteModelConfirm_<?php echo $model['id']; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                        <form class="form-horizontal" method="post" action="{{url('/delete-model')}}">
                                          {{ csrf_field() }}
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row" >
                                                <div class="col-md-12">
                                                    <h3>Are Your Sure Want to Delete?</h3>
                                                    <h4><?php echo $model['model_name'] ?></h4>
                                                    <input type="hidden" name="id" value="<?php echo $model['id']; ?>"/>
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
                                <td><button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModelConfirm_<?php echo $model['id']; ?>"><i class="glyphicon glyphicon-trash"></i>
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