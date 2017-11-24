@extends('layout.master')

@section('title')
{{$data['title']}}: <u style="font-weight: bold;">Customers</u>
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="vendors/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="vendors/datatables/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/responsive.dataTables.css">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="css/pages/datatables.css">

<script type="text/javascript" src="{{url('angular/customer_app.js')}}"></script>
<script>
    var insert_customer_url = "{{url('/customer/add-ajax')}}";
</script>
    
    
    
<div class="row" ng-controller="customerCTRL">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-table"></i> Customers 
                    | <a style="border-bottom: 1px dashed #FF0000;" href="" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle" ></i> Add New</a>
                    | <a style="border-bottom: 1px dashed #FF0000;" href="" data-toggle="modal" data-target="#uploadCustomerFile"><i class="fa fa-file-excel-o" ></i> Upload Customer</a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                        <tr>
                            <th>STATUS</th>
                            <th>NAME</th>
                            <th>ADDRESS</th>
                            <th>CONTACT</th>
                            <th>EDIT</th>
                            <th>DELETE</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($data['customers'] as $customer){
                            ?>
                            <tr>
                                <td>
                                    <?php 
                                        if($customer['deleted']==1){ 
                                            echo "<label class='label label-danger'>Deleted</label>";
                                        }
                                        else{
                                            echo "<label class='label label-success'>Active</label>";
                                        }
                                    ?>
                                </td>
                                <td><?php echo $customer['name']; ?></td>
                                <td><?php echo $customer['address']; ?></td>
                                <td><?php echo $customer['contact']; ?></td>
                                <td>

                                    <form method="get" action="{{url('/customers/edit')}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$customer['id']}}" />
                                        <button type="submit" class="btn btn-sm btn-warning" ><i class="fa fa-edit"></i></button>
                                    </form>

                                    <!-- Modal -->
                                    <div id="deleteCustomerConfirm_<?php echo $customer['id']; ?>" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                        <form class="form-horizontal" method="post" action="{{url('/delete-customer')}}">
                                          {{ csrf_field() }}
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row" >
                                                <div class="col-md-12">
                                                    <h3>Are Your Sure Want to Delete?</h3>
                                                    <h4><?php echo $customer['name'] ?></h4>
                                                    <input type="hidden" name="id" value="<?php echo $customer['id']; ?>"/>
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
                                <td><button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteCustomerConfirm_<?php echo $customer['id']; ?>"><i class="glyphicon glyphicon-trash"></i>
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
    
    
    









<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <form class="form-horizontal" method="post" action="{{url('/customers')}}">
          {{ csrf_field() }}
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Customer</h4>
          </div>
          <div class="modal-body">
              <div style="padding: 15px;">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required="" />
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control"  />
                </div>
                <div class="form-group">
                    <label>Contact</label>
                    <input type="text" name="contact" class="form-control"  />
                </div> 
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" >Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>  

<!-- Modal customer upload-->
<div id="uploadCustomerFile" class="modal fade" role="dialog" >
  <div class="modal-dialog">
    <!-- Modal content-->
    <form class="form-horizontal" method="post" action="{{url('/customers')}}">
          {{ csrf_field() }}
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Customer</h4>
          </div>
          <div class="modal-body">
              <div style="padding: 15px;">
                  
                <div class="form-group">
                    <label style="color: green">Now Select customer spreadsheets (Only .csv files)</label>
                    <input type="file"  name="file" on-read-file="showContent($fileContent)"   required="" />
                </div>
                <div style="padding: 5px; background: #ebe8e8; border: 1px solid silver">
                      <label>Note:</label>
                      <p>In SpreadSheet, The first column must be <b>customer name</b>, <br>
                      second column must be <b>contact person</b>, <br>
                      third column must be <b>address</b> and <br>
                      the fourth column must be <b>contact</b>.<br></p>
                      <small style="color: red;">*Please don't include these titles on the CSV file.*</small>
                  </div>
                  <div ng-if="waiting_text" style="color: red; font-size: 14px;">
                      <span>Please Wait...</span>
                  </div>
                  <hr>
                  <div>
                      <label>Total:@{{total}}</label> | 
                      <label style="color: green;">Success:@{{total_success}}</label> | 
                      <label style="color: red;">Failed:@{{total_failed}}</label> | 
                      <label ng-if="waiting_text_global" style="color: red;">Please Wait...</label>
                  </div>
                  <div style="max-height: 150px; overflow-y: scroll; font-size: 11px;">
                      <table>
                            <tr ng-repeat="p in processed">
                                <td>@{{p.name}}</td>
                                <td>@{{p.address}}</td>
                            </tr>
                        </table>
                  </div>
                
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" >Submit</button>
          </div>
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