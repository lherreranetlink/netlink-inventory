@extends('layout.master')

@section('title')
{{$data['title']}}: <u style="font-weight: bold;">User</u>
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
                    <i class="fa fa-fw fa-table"></i> Users <small>(User Credentials)</small> | <a style="border-bottom: 1px dashed #FF0000;" href="" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle" ></i> Add New User</a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <style type="text/css">
                        .float-my-children > * {
                            float:left;
                            margin-right:5px;
                        }
                        .clearfix {
                            *zoom:1 /* for IE */
                        }
                        .clearfix:before,
                        .clearfix:after {
                            content: " ";
                            display: table;
                        }

                        .clearfix:after {
                            clear: both;
                        }

                    </style>
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                        <tr>
                            <th>ROLE</th>
                            <th >NAME</th>
                            <th>EMAIL</th>
                            <th>STATUS</th>
                            <th>DELETE</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($data['users'] as $user){
                            ?>
                            <tr>
                                <td><?php 
                                    if($user['role']=="admin"){
                                        echo "<label class='label label-danger'>admin</label>";
                                    }
                                    else{
                                        echo "<label class='label label-success'>user</label>";
                                    }
                                ?></td>
                                
                                <td>
                                    <div class="clearfix float-my-children"><img style="height: 30px !important;" class="thumbnail img-responsive"
                                        src="<?php 
                                        if($user['profile_pic']!=""){
                                            echo $user['profile_pic'];
                                        }
                                        else{
                                            echo url('profile_pics/user-profile.png');
                                        }
                                        ?>" />
                                        <span><?php echo $user['name']; ?></span></div>
                                </td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <?php 
                                    if($user['deleted']==1){ 
                                        echo "<label class='label label-danger'>Deleted</label>";
                                    }
                                    else{
                                        echo "<label class='label label-success'>Active</label>";
                                    }
                                ?>
                                </td>
                                <!--
                                <td>
                                    <?php 
                                        if($user['deleted']==1){
                                            echo "<label class='label label-danger'>Deleted</label>";
                                        }
                                        else{
                                            echo "<label class='label label-success'>Active</label>";
                                        }
                                    ?>
                                </td>
                                -->
                                <td>
                                    <!-- Modal DELETE-->
                                    <div id="deleteUserConfirm_<?php echo $user['id']; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                        <form class="form-horizontal" method="post" action="{{url('/delete-user')}}">
                                          {{ csrf_field() }}
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row" >
                                                <div class="col-md-12">
                                                    <h3>Are Your Sure Want to Delete?</h3>
                                                    <h4><?php echo $user['name'] ?></h4>
                                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>"/>
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
                                    
                                    <!-- Modal EDIT-->
                                    <div id="editUserConfirm_<?php echo $user['id']; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                        <form class="form-horizontal" method="post" action="{{url('/user/edit')}}">
                                          {{ csrf_field() }}
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Update User</h4>
                                          </div>
                                          <div class="modal-body">
                                              <input type="hidden" value="<?php echo $user['id']; ?>" name="id" />
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <input type="password" name="password" class="form-control" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Barcode</label>
                                                        <input type="password" name="barcode" value="<?php echo $user['tag_code']; ?>" class="form-control" />
                                                    </div>  
                                                    <div class="form-group">
                                                        <label>Status</label>

                                                        <select name="deleted" class="form-control" >
                                                            <option <?php if($user['deleted']==1){echo 'selected';} ?> value="1">Deleted</option>
                                                            <option <?php if($user['deleted']==0){echo 'selected';} ?> value="0">Active</option>
                                                        </select>
                                                    </div>
                                                                                    </div>
                                              
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success" >Update <i class="fa fa-check"></i></button>
                                          </div>
                                          </form>
                                        </div>

                                      </div>
                                    </div>
                                    
                                    
                                    <?php if($user['email']!='ssingh@netlinkvoice.com'){ ?>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteUserConfirm_<?php echo $user['id']; ?>"><i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    <?php } ?>
                                    <?php if($user['email']!='ssingh@netlinkvoice.com'){ ?>
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#editUserConfirm_<?php echo $user['id']; ?>"><i class="glyphicon glyphicon-edit"></i>
                                    </button>
                                    <?php } ?>
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
        <form style="padding: 20px;" class="form-horizontal" method="post" action="{{url('/users')}}">
          {{ csrf_field() }}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New User Form</h4>
      </div>
      <div class="modal-body">
            <div style="padding: 15px;">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required="" />
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password" class="form-control"  />
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="form-control"  />
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