@extends('layout.master')

@section('title')
Profile & Activities
@endsection



@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>
<link rel="stylesheet" href="{{URL::to('css/ng-img-crop.css')}}" type="text/css" />
<script type="text/javascript" src="{{URL::to('angular/profile_app.js')}}"></script>


<script>
    var redirect_url = "{{URL::to('/profile')}}";
    var change_profile_pic_url  =  "{{URL::to('/profile/change_pic_ajax')}}";
    var user_id = "{{Auth::user()->id}}";
    var checkins_url = "{{url('/profile/checkins')}}";
    var checkouts_url = "{{url('/profile/checkouts')}}";
    var location_change_url = "{{url('/profile/location-change')}}";
    
</script>
<div class="row " style="margin-top:2px;" ng-controller="profileCtrl">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-table"></i> Profile 
                    <!--
                    | <a class="{{ Request::path() == 'my-checkins' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/my-checkins')}}" ><i class="fa fa-arrow-down" ></i> My Check-In</a>
                    | <a class="{{ Request::path() == 'my-checkouts' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/my-checkouts')}}" ><i class="fa fa-search" ></i> My Check Outs</a>
                    -->
                </h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-info-circle"></i> Detail </a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <center><img style="height: 90px !important;" class="text-center profile-user-img img-responsive img-circle" src="<?php 
                                if(Auth::user()->profile_pic!=""){
                                    echo Auth::user()->profile_pic;
                                }
                                else{
                                    echo url('profile_pics/user-profile.png');
                                }
                                ?>" /></center>

                               <h3 class="profile-username text-center">{{Auth::user()->name}} 
                               <p class="text-muted text-center">sdaasd

                               <ul class="list-group list-group-unbordered" style="text-align: left; font-size: 13px !important;">


                                 <li class="list-group-item">
                                   {{Auth::user()->email}}
                                 </li>
                                 <li class="list-group-item">
                                     <a style="cursor: pointer;" data-toggle="modal" data-target="#changePassword"><i class="fa fa-key"></i> Change Password</a>
                                 </li>
                                 <li class="list-group-item">
                                     <a style="cursor: pointer;" data-toggle="modal" data-target="#profilePic"><i class="fa fa-image"></i> Change Picture</a>
                                 </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-lg-9">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> Activities </a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <style type="text/css" >
                                .navbar-nav > li > a {
                                    padding-bottom: 6px;
                                    padding-top: 6px;
                                }
                                .navbar{
                                    min-height: 0px !important;
                                }
                                .profile_activities th{
                                    cursor: pointer;
                                }
                                
                            </style>
                            <nav class="navbar navbar-default" style="font-size: 12px;">
                                <div class="container-fluid">
                                  <ul class="nav navbar-nav">
                                      <li style="cursor: pointer;" ng-click="checkinClick()" class="@{{checkin_active}}"><a  ><i class="fa fa-arrow-down"></i> Checkins</a></li>
                                      <li style="cursor: pointer;" ng-click="checkoutClick()" class="@{{checkout_active}}"><a  ><i class="fa fa-arrow-up"></i> Checkouts</a></li>
                                      <li style="cursor: pointer;" ng-click="locationClick()" class="@{{location_active}}"><a ><i class="fa fa-map-marker"></i> Location Change</a></li>
                                  </ul>
                                </div>
                            </nav>
                            <div class="profile_activities" style="padding: 2px">
                                <span style="color: red;" ng-show="data_processing">Please Wait....</span> 
                                <i class="fa fa-search"></i> Search or Scan Barcode
                                <input type="text" ng-model="search" class="form-control" placeholder="Search or Scan Barcode">    
                                <div ng-if="checkin_active=='active'">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th ng-click="sort('created_at')">Date
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='created_at'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('inout.mac')">MAC
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='inout.mac'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('inout.identifier')">Identifier
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='inout.identifier'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('model.model_number')">Model
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='model.model_number'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr dir-paginate="checkin in checkins |orderBy:sortKey:reverse | filter:search | itemsPerPage:10">
                                                <td >@{{checkin.created_at | cmdate:'MMM, dd yyyy hh:mm a'}}</td>
                                                <td >@{{checkin.inout.mac}}</td>
                                                <td >@{{checkin.inout.identifier}}</td>
                                                <td >@{{checkin.model.model_number}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <dir-pagination-controls max-size="10" direction-links="true" boundary-links="true" ></dir-pagination-controls>
                                </div>
                                
                                <div ng-if="checkout_active=='active'">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th ng-click="sort('created_at')">Date
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='created_at'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('inout.mac')">MAC
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='inout.mac'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('inout.identifier')">Identifier
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='inout.identifier'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('model.model_number')">Model
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='model.model_number'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>   
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr dir-paginate="checkout in checkouts |orderBy:sortKey:reverse  | filter:search | itemsPerPage:10">
                                                <td>@{{checkout.created_at | cmdate:'MMM, dd yyyy hh:mm a'}}</td>
                                                <td>@{{checkout.inout.mac}}</td>
                                                <td>@{{checkout.inout.identifier}}</td>
                                                <td>@{{checkout.model.model_number}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <dir-pagination-controls max-size="10" direction-links="true" boundary-links="true" ></dir-pagination-controls>
                                </div>
                                
                                <div ng-if="location_active=='active'">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th ng-click="sort('created_at')">Date
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='created_at'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('inout.mac')">MAC
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='inout.mac'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('inout.identifier')">Identifier
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='inout.identifier'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>
                                                <th ng-click="sort('model.model_number')">Model
                                                    <span class="glyphicon sort-icon" ng-show="sortKey=='model.model_number'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                                </th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr dir-paginate="location in location_change |orderBy:sortKey:reverse  | filter:search | itemsPerPage:10">
                                                <td>@{{location.created_at | cmdate:'MMM, dd yyyy hh:mm a'}}</td>
                                                <td>@{{location.inout.mac}}</td>
                                                <td>@{{location.inout.identifier}}</td>
                                                <td>@{{location.model.model_number}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <dir-pagination-controls max-size="10" direction-links="true" boundary-links="true" ></dir-pagination-controls>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                <!-- PROFILE PIC MODAL------------------------>
                <style>
                    .cropArea {
                        background: #E4E4E4;
                        overflow: hidden;
                        width:500px;
                        height:350px;
                      }
                </style>
                <div class="modal fade" id="profilePic"  tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Profile Image</h4>
                      </div>
                      <div class="modal-body">
                        <div>Select an image file: <input type="file" id="fileInput" /></div>
                        <div class="cropArea responsive img-responsive">
                          <img-crop area-type="square" image="myImage" result-image="myCroppedImage"></img-crop>
                        </div>

                      </div>
                      <div class="modal-footer">
                        <span ng-if="processing" style="color: red; font-weight: bold;">Please Wait...</span>
                        <button ng-click="" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button ng-click="changePic()"  type="button" class="btn btn-primary" >Save</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                
                
                <!-- PASSWORD CHANGE MODAL------------------------>
                <div class="modal fade" id="changePassword"  role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Change Password</h4>
                      </div>
                        <form style="padding: 20px;" class="form-horizontal" method="post" action="{{url('/profile/change-password')}}" >
                            {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="hidden" value="{{Auth::user()->id}}" name="id" />
                                <input type="password" name="password" class="form-control" required="" />
                            </div>
                            <div class="form-group">
                                <label>Re-type Password</label>
                                <input type="password" name="re_password" class="form-control" required="" />
                            </div>

                        </div>
                        <div class="modal-footer">
                          <button  type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button   type="submit" class="btn btn-primary" >Update Password</button>
                        </div>
                      </form>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->





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

