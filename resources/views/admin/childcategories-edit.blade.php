@extends('layout.master')

@section('title')
{{$data['title']}}: <u style="font-weight: bold;">Child Categories</u>
@endsection



@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>   
    
    
    
<div class="row " style="margin-top:2px;">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-table"></i> Child Category : {{$data['childcategory']->name}} </a>
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
                <div class="col-md-4">
                    <form class="form-horizontal" method="post" action="{{url('/childcategories/edit')}}">
                    {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{$data['childcategory']->id}}" />

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{$data['childcategory']->name}}" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Parent Sub Category</label><br>
                        <select style="width: 170px"  name="sub_category_id" class="select2">
                            <option value="">Select</option>
                            <?php 
                            foreach($data['subcategories'] as $cat){
                            ?>
                            <option value="<?php echo $cat->id; ?>" <?php if($data['childcategory']->subcategory_id==$cat->id){ echo "selected";} ?> ><?php echo $cat->name; ?></option>
                            <?php 
                            }
                            ?>

                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>

                        <select name="deleted" class="form-control" >
                            <option <?php if($data['childcategory']->deleted==1){echo 'selected';} ?> value="1">Deleted</option>
                            <option <?php if($data['childcategory']->deleted==0){echo 'selected';} ?> value="0">Active</option>
                        </select>
                    </div>

                    <input type="submit" class="btn btn-sm btn-success" value="Update" />
                    <a href="{{url('/childcategories')}}" class="btn btn-sm btn-default">Cancel</a>
                </form>
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