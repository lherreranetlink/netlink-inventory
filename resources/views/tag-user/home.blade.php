@extends('tag-user.master')

@section('title')
{{$data['title']}}
@endsection

@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{url('angular/home_app.js')}}"></script>

<script>
    var add_checkout_url = "{{url('/checkouts/add-process-ajax')}}";
    var change_location_url = "{{url('/checkouts/change-location-process-ajax')}}";
    var mfg_url = "{{url('/manufacturers-json')}}";
    var category_url = "{{url('/categories-json')}}";
    var location_url = "{{url('/locations-json')}}";
    var sublocation_url = "{{url('/sublocations-by-location-json')}}";
    var childlocation_url = "{{url('/childlocations-by-sublocation-json')}}";
    var url = "{{url('/checkouts')}}";
    var checkin_back_redirect = "{{url('/checkins')}}";
    var checkin_back_url = "{{url('/checkin-back-ajax-process')}}";
    var location_change_redirect = "{{url('/location-changes')}}";
    var customer_url = "{{url('/customers-json')}}";
    var get_checkins_by_barcode_url = "{{url('/get-checkin-barcode-ajax')}}";
</script>
<div class="row " style="margin-top:2px;" ng-controller="homeCTRL">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-home"></i> Home <small>< INVENTORY ></small> </a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="col-lg-12 col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-barcode"></i> Scan Barcode </a> 
                                | <a style="border-bottom: 1px dashed #FF0000;" href="{{url('/tag-user/bulk-checkin')}}" ><i class="fa fa-arrow-down" ></i> Bulk Check-In</a>
                                | <a style="border-bottom: 1px dashed #FF0000;" href="{{url('/tag-user/bulk-checkout')}}" ><i class="fa fa-arrow-up" ></i> Bulk Check-Out</a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <label>Barcode:</label>
                            <input id="inputError2" class="form-control barcode-input" ng-model="barcode" ng-keyup="$event.keyCode == 13 && getProductDetail()" placeholder="Please Scan Barcode and Press Enter..." /><br />
                                <div style="color: red; font-size: 16px;" ng-if="processingDelay">Please Wait...</div>
                                
                                <div ng-if="checkin.length==0 && barcode!='' && ajax_done"  style="padding: 5px; color: red; ">
                                    <label>Record not found for this barcode!!!</label> <br>
                                    <label>If you want to checkin, please press check In button.</label> <br>
                                    <a href="{{url('/check-in')}}/@{{barcode}}" class="btn btn-success btn-sm ">New Item? Check In <i class="fa fa-arrow-down"></i></a>
                                </div>
                                <!--already checked out -->
                                <div class="col-md-12 " ng-if="checkin.length!=0 && checkin.checkout_at!=null && barcode.length!=0" ng-show="showSuggestion"  style="padding: 10px !important; border: 1px solid #e9e7e7; border-radius: 3px;">
                                    <div class="col-md-9">
                                        <table class="table table-striped table-bordered">
                                            <tr><td colspan="2"><label style="color: red;"><i class="fa fa-close"></i> Already Checked out!! <b style="border-bottom: 1px dashed silver;">@{{barcode}}</b></label> </td></tr>

                                            <tr style="font-weight: bold;">
                                                <td>Model</td>
                                                <td>@{{checkin.manufacturer}} @{{checkin.model_number}}</td>
                                            </tr>
                                            <tr ng-if="checkin.mac.length!=0">
                                                <td>MAC</td>
                                                <td>@{{checkin.mac}}</td>
                                            </tr>
                                            <tr style="color: darkcyan; font-weight: bold;">
                                                <td>Customer</td>
                                                <td>
                                                    @{{checkin.customer}}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Category</td>
                                                <td>
                                                    @{{checkin.category}}
                                                    <span ng-if="checkin.subcategory.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.subcategory}}</span>
                                                    <span ng-if="checkin.childcategory.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.childcategory}}</span>
                                                </td>
                                            </tr>
                                            <tr ng-if="checkin.is_identifier=='yes'">
                                                <td>Identifier</td>
                                                <td>@{{checkin.identifier_name}}: @{{checkin.identifier}}</td>
                                            </tr>
                                            <tr >
                                                <td>Checked-In</td>
                                                <td>@{{checkin.checkin_at | cmdate:'MMM, dd yyyy hh:mm'}} by <span style="border-bottom: 1px dashed silver;">@{{checkin.checkin_by}}</span></td>
                                            </tr>
                                            <tr style="color: red;">
                                                <td>Checked-Out:</td>
                                                <td>@{{checkin.checkout_at | cmdate:'MMM, dd yyyy hh:mm'}} by @{{checkin.checkout_by_name}}</td>
                                            </tr>
                                        </table>
                                        <label style="font-weight: bold; border-bottom: 1px dashed silver;"><i class="fa fa-info-circle"></i> Not Available for Check Out</label> <br>
                                    </div>
                                    <div class="col-md-3">
                                        <img ng-if="checkin.picture.length==0" src="{{url('/img/authors/avatar1.jpg')}}" width="200" class="thumbnail img-responsive" />
                                        <img ng-if="checkin.picture.length!=0" data-ng-src="@{{checkin.picture}}" width="200" class="thumbnail img-responsive" />
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <button ng-if="showSuggestion" type="button" ng-click="checkinBackClick(checkin)"  class="btn btn-sm btn-success" data-toggle="modal" data-target="#checkinBack" >Check-In Back <i class="fa fa-refresh"></i></button>
                                    </div>
                                    
                            </div>
                            <!-- Available -->
                            <div class="col-md-12" style="padding: 10px !important; border: 1px solid #e9e7e7; border-radius: 3px;" ng-show="showSuggestion" ng-if="checkin.length!=0 && checkin.checkout_at==null && barcode.length!=0 && (checkin.checkout_by==0 ||checkin.checkout_by==null)">
                                <div class="col-md-9">
                                    <table class="table table-striped table-bordered">
                                        <tr><td colspan="2"><label style="color: green;"><i class="fa fa-check"></i> Available in Stock! <b style="border-bottom: 1px dashed silver;">@{{barcode}}</b></label> </td></tr>
                                        <tr style="font-weight: bold;">
                                            <td>Model</td>
                                            <td>@{{checkin.manufacturer}} @{{checkin.model_number}}</td>
                                        </tr>
                                        <tr ng-if="checkin.mac.length!=0">
                                            <td>MAC</td>
                                            <td>@{{checkin.mac}}</td>
                                        </tr>
                                        <tr>
                                            <td>Cur. Location</td>
                                            <td>
                                                @{{checkin.location}}
                                                <span ng-if="checkin.sublocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.sublocation}}</span>
                                                <span ng-if="checkin.childlocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.childlocation}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Category</td>
                                            <td>
                                                @{{checkin.category}}
                                                <span ng-if="checkin.subcategory.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.subcategory}}</span>
                                                <span ng-if="checkin.childcategory.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.childcategory}}</span>
                                            </td>
                                        </tr>
                                        <tr ng-if="checkin.is_identifier=='yes'">
                                            <td>Identifier</td>
                                            <td>@{{checkin.identifier_name}}: @{{checkin.identifier}}</td>
                                        </tr>
                                        <tr >
                                            <td>Checked-In</td>
                                            <td>@{{checkin.checkin_at | cmdate:'MMM, dd yyyy hh:mm'}} by <span style="border-bottom: 1px dashed silver;">@{{checkin.checkin_by}}</span></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <img ng-if="checkin.picture.length==0" src="{{url('/img/authors/avatar1.jpg')}}" width="200" class="thumbnail img-responsive" />
                                    <img ng-if="checkin.picture.length!=0" data-ng-src="@{{checkin.picture}}" width="200" class="thumbnail img-responsive" />
                                </div>
                                <div class="col-md-12 col-lg-12">
                                        <button type="button" ng-click="checkOutModal(checkin)"  class="btn btn-xs btn-primary" data-toggle="modal" data-target="#checkOut" >Check-Out</button>
                                        <button  type="button" ng-click="changeLocationClick(checkin)"  class="btn btn-xs btn-success" data-toggle="modal" data-target="#changeLocation" >Change Location</button>
                                        
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>

<!-- Modal -->
<div id="checkOut" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Check Out</h4>
      </div>
      <div class="modal-body">
          <span style="color: red;" ng-if="customerLoading">Please Wait....<br> Processing...</span>
          <div class="form-group" ng-show="customerLoading==false">
              @{{name}}
              <label>Please Select Customer <small>(Optional)</small></label> <br>
                <select style="width: 100%" 
                        ng-model="selectedCustomer" 
                        ng-change="changeCustomer(selectedCustomer)" 
                        ng-options="cust.name for cust in customers"
                        class="select2">
                    <option value="">Select</option>
                </select><span style="color: red;" ng-if="catLoading">Loading...</span>
            </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-success" ng-click="addCheckout()" >Check Out <i class="fa fa-check"></i></button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="cancelCheckOut()" >Cancel <i class="fa fa-close"></i></button>
      </div>
    </div>

  </div>
</div>


<!-- Modal Location-->
<div id="changeLocation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Location</h4>
      </div>
      <div class="modal-body">
        <div class="form-group" style="background: #e9e7e7; padding: 6px; border: 1px solid silver">
            <label>Current Location</label> <br>
             @{{checkin.location}}
            <span ng-if="checkin.sublocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.sublocation}}</span>
            <span ng-if="checkin.childlocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.childlocation}}</span>
        </div>
        <div class="form-group">
            <label>Location</label> <br>
            <select style="width: 100%" 
                    ng-model="selectedLocation" 
                    ng-change="locationChange(selectedLocation)" 
                    ng-options="cat.name for cat in locations"
                    name="location_id" class="select2">
                <option value="">Select</option>
            </select><span style="color: red;" ng-if="catLoading">Loading...</span>
        </div>

        <span style="color: red;" ng-if="subLoading">Loading...</span>
        <div class="form-group" ng-show="is_sublocation">
            <label>Sub Location</label> <br>
            <select style="width: 100%" 
                    ng-model="selectedSubLocation" 
                    ng-change="sublocationChange(selectedSubLocation)" 
                    ng-options="subcat.name for subcat in sublocations"
                    name="sublocation_id" class="select2">
                <option value="">Select</option>
            </select> 
        </div>
        <span style="color: red;" ng-if="childLoading">Loading...</span>
        <div class="form-group" ng-show="is_childlocation">
            <label>Child Location</label> <br>
            <select style="width: 100%" 
                    ng-model="selectedChildLocation" 
                    ng-change="childlocationChange(selectedChildLocation)" 
                    ng-options="childcat.name for childcat in childlocations"
                    name="childlocation_id" class="select2">
                <option value="">Select</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" ng-click="changeLocationProcess()" >Change Location <i class="fa fa-check"></i></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="cancelChangeLocationProcess()" >Cancel <i class="fa fa-close"></i></button>
      </div>
    </div>

  </div>
</div>



<!-- Modal Location Change but with checkin state -->
<div id="checkinBack" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Location</h4>
      </div>
      <div class="modal-body">
       
        <div class="form-group" style="background: #e9e7e7; padding: 6px; border: 1px solid silver">
            <label>Customer to whome this item checked out for</label> <br>
            <b style="color: green;">@{{checkin.customer}}</b>
        </div>
        <div class="form-group">
            <label>Location</label> <br>
            <select style="width: 100%" 
                    ng-model="selectedLocation" 
                    ng-change="locationChange(selectedLocation)" 
                    ng-options="cat.name for cat in locations"
                    name="location_id" class="select2">
                <option value="">Select</option>
            </select><span style="color: red;" ng-if="catLoading">Loading...</span>
        </div>

        <span style="color: red;" ng-if="subLoading">Loading...</span>
        <div class="form-group" ng-show="is_sublocation">
            <label>Sub Location</label> <br>
            <select style="width: 100%" 
                    ng-model="selectedSubLocation" 
                    ng-change="sublocationChange(selectedSubLocation)" 
                    ng-options="subcat.name for subcat in sublocations"
                    name="sublocation_id" class="select2">
                <option value="">Select</option>
            </select> 
        </div>
        <span style="color: red;" ng-if="childLoading">Loading...</span>
        <div class="form-group" ng-show="is_childlocation">
            <label>Child Location</label> <br>
            <select style="width: 100%" 
                    ng-model="selectedChildLocation" 
                    ng-change="childlocationChange(selectedChildLocation)" 
                    ng-options="childcat.name for childcat in childlocations"
                    name="childlocation_id" class="select2">
                <option value="">Select</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-success" ng-click="checkInBackProcess()" >Check-In Back <i class="fa fa-refresh"></i></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="cancelChangeLocationProcess()" >Cancel <i class="fa fa-close"></i></button>
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
    //$("[data-mask]").inputmask();
    //$("#did").inputmask({"mask": "(999) 999-9999"});
    //alert("sdfsdf");
    $(".select2").select2();
  });
</script>



<!--

            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon pull-left">
                            <i class="fa fa-eye text-warning"></i>
                        </div>
                        <div class="text-right">
                            <h3 class="text-dark"><b>3752</b></h3>
                            <p>Daily Visits</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon pull-left">
                            <i class="fa fa-opencart text-success"></i>
                        </div>
                        <div class="text-right">
                            <h3><b id="widget_count3">3251</b></h3>
                            <p>Sales status</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon pull-left">
                            <i class="fa fa-thumbs-o-up text-danger"></i>
                        </div>
                        <div class="text-right">
                            <h3 class="text-dark"><b>1532</b></h3>
                            <p>Hits</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon pull-left">
                            <i class="fa fa-hand-pointer-o text-info"></i>
                        </div>
                        <div class="text-right">
                            <h3 class="text-dark"><b>1252</b></h3>
                            <p>Subscribers</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel ">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Sales
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div id="sales_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class=" panel widget-timeline">
                        <div class="panel-heading">
                            <h3 class="panel-title">Timeline</h3>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li>
                                    <img src="img/authors/avatar.jpg" alt="" class="pull-left">
                                    <div class="timeline">
                                        <span><strong>Walters</strong> Started following Designs
                                            by <a href="javascript:void(0)">Torres</a>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <img src="img/authors/avatar5.jpg" alt="" class="pull-left">
                                    <div class="timeline">
                                        <span><strong>Martin</strong> shared the Icon designs of
                                             <a href="javascript:void(0)">Alan Rivera</a>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <img src="img/authors/avatar6.jpg" alt="" class="pull-left">
                                    <div class="timeline">
                                        <span><strong>Stanley</strong> completed the task assigned
                                            by <a href="javascript:void(0)">Ben Adams</a>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <img src="img/authors/avatar3.jpg" alt="" class="pull-left">
                                    <div class="timeline">
                                        <span><strong>Thomas</strong> liked an article
                                            by <a href="javascript:void(0)">Randy Spencer</a>
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel product-details">
                        <div class="panel-heading">
                            <h3 class="panel-title">Products Details</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped text-center" id="product-details">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center"><strong>Id</strong></th>
                                                <th class="text-center"><strong>Product Name</strong></th>
                                                <th class="text-center"><strong>Price</strong></th>
                                                <th class="text-center"><strong>Sales</strong></th>
                                                <th class="text-center"><strong>Ratings</strong></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>7897898</td>
                                                <td>Becky Barnes</td>
                                                <td>$340</td>
                                                <td>3,080</td>
                                                <td class="text-warning">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star-half-o" aria-hidden="true"></i>
                                                    <i class="fa fa-star-o" aria-hidden="true"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>7897898</td>
                                                <td>Jayden Hunter</td>
                                                <td>$340</td>
                                                <td>3,080</td>
                                                <td class="text-warning">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star-o" aria-hidden="true"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>7897898</td>
                                                <td>Wallace boyd</td>
                                                <td>$340</td>
                                                <td>3,080</td>
                                                <td class="text-warning">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star-half-o" aria-hidden="true"></i>
                                                    <i class="fa fa-star-o" aria-hidden="true"></i>
                                                    <i class="fa fa-star-o" aria-hidden="true"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>7897898</td>
                                                <td>Randy Spencer</td>
                                                <td>$340</td>
                                                <td>3,080</td>
                                                <td class="text-warning">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star-half-o" aria-hidden="true"></i>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-12 p-0">
                                    <span id="product_status"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
-->
@endsection