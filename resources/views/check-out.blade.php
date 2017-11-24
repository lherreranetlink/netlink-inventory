@extends('layout.master')

@section('title')
{{$data['title']}}: <u style="font-weight: bold;">Single Checkout</u>
@endsection



@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{url('angular/checkout_app.js')}}"></script>

<script>
    var add_checkout_url = "{{url('/checkouts/add-process-ajax')}}";
    var change_location_url = "{{url('/checkouts/change-location-process-ajax')}}";
    var mfg_url = "{{url('/manufacturers-json')}}";
    var category_url = "{{url('/categories-json')}}";
    var location_url = "{{url('/locations-json')}}";
    var sublocation_url = "{{url('/sublocations-by-location-json')}}";
    var childlocation_url = "{{url('/childlocations-by-sublocation-json')}}";
    var url = "{{url('/checkouts')}}";
    var location_change_redirect = "{{url('/location-changes')}}";
    var customer_url = "{{url('/customers-json')}}";
    var get_checkins_by_barcode_url = "{{url('/get-checkin-barcode-ajax')}}";
</script>
<div class="row " style="margin-top:2px;" ng-controller="checkoutCTRL">
    <div class="col-lg-12 col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="{{ Request::path() == 'check-out' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/check-out')}}" ><i class="fa fa-arrow-up" ></i> Single Check-Out</a>
                    | <a class="{{ Request::path() == 'bulk-checkout' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/bulk-checkout')}}" ><i class="fa fa-arrow-up" ></i> Bulk Check-Out</a>
                    
                    <!--
                    | <a class="{{ Request::path() == 'checkouts' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/checkouts')}}" ><i class="fa fa-search" ></i> Recent Check-Outs</a>
                    -->
                </h3>
            </div>
            <div class="panel-body">
                <!--
                <div class="row">
                    <div class="col-lg-12" >
                        <a style="text-align: center;" href=""><div style="max-width: 80px; background: #e1dddd; border: gray 1px solid; border-radius: 3px; padding: 5px">Bulk Check-Out</div></a>
                    </div>
                </div>
                -->
                <div class="alert alert-danger" ng-show="final_result.statuscode!=200&&final_result.statuscode.length!=0">
                    <span>Error:@{{final_result.statuscode}} @{{final_result.statustext}}</span>
                    Operation Unsuccessful, Please Contact Developer!
                </div>
                <form style="padding: 20px;" class="form-horizontal col-lg-9 col-md-9" method="post" >
                    {{ csrf_field() }}
                    <div class="form-group">
                        <lable>Barcode: </lable>
                        <input autofocus="true" class="form-control barcode-input" style="" ng-model="barcode" ng-keyup="$event.keyCode == 13 && getProductDetail()" placeholder="Please Scan Barcode and Press Enter" />   
                        <div style="color: red; font-size: 16px;" ng-if="processingDelay">Please Wait...</div>
                    </div>
                    <span ng-show="showNotFound" style="color: red;" ng-if="checkin.length==0">Record Not Found!</span>
                    <div ng-if="showSuggestion==false">
                        <label style="color: darkred"> << OR >> </label><br>
                        <label>No Barcode ? </label> <a ng-if="is_manual!==true" ng-click="toggleManualBarcode(manual)" class="btn btn-success btn-xs">Yes</a>
                        <a ng-if="is_manual==true" ng-click="toggleManualBarcode(manual)" class="btn btn-danger btn-xs">No</a>
                    </div>
                    
                    <div class="form-group" ng-show="is_manual">
                        <label>Manufacturer</label><br>
                        <select style="width: 100%" 
                                ng-model="selectedMfg" 
                                ng-change="changeCategoryManufacturer(selectedMfg)" 
                                ng-options="mfg.name for mfg in manufacturers"
                                name="location_id" class="select2">
                            <option value="">Select</option>
                        </select><span style="color: red;" ng-if="mfgLoading">Loading...</span>
                    </div>
                    
                    <div class="form-group" ng-show="is_manual">
                        <label>Category</label> <br>
                        <select style="width: 100%" 
                                ng-model="selectedCategory" 
                                ng-change="changeCategoryManufacturer(selectedCategory)" 
                                ng-options="cat.name for cat in categories"
                                name="category_id" class="select2">
                            <option value="">Select</option>
                            
                        </select><span style="color: red;" ng-if="catLoading">Loading...</span>
                    </div>
                    
                    <div class="form-group" ng-show="is_manual">
                        <label>Model Name/Number </label><br>
                        <select style="width: 100%" 
                                ng-model="selectedModel" 
                                ng-change="changeModel(selectedModel)" 
                                ng-options="model.model_number for model in models"
                                name="location_id" class="select2">
                            <option value="">Select</option>
                        </select><span style="color: red;" ng-if="modelLoading">Loading...</span>
                        <label style="color: green">Did not find any model?
                        <?php if(Auth::user()->role=='admin'){
                        ?>
                             | <a href="#"><i class="fa fa-plus-circle"></i> Add New</a></label>
                        
                         <?php }
                         else{
                             echo "Please Contact Admin User.";
                         }
                        ?>
                    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="col-md-12" style="padding: 10px !important; border: 1px solid #e9e7e7; border-radius: 3px;" ng-show="showSuggestion" ng-if="more_checkins_found==false && checkin.checkout_at==null && (checkin.checkout_by==0 ||checkin.checkout_by==null)">
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
                                <tr ng-if="checkin.is_identifier=='yes' && checkin.identifier_name2.length>0">
                                    <td>Identifier #2</td>
                                    <td>@{{checkin.identifier_name2}}: @{{checkin.identifier2}}</td>
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
                            <button ng-if="showSuggestion" type="button" ng-click="checkOutModal(checkin)"  class="btn btn-sm btn-primary" data-toggle="modal" data-target="#checkOut" >Check-Out</button>
                            <button ng-if="showSuggestion" type="button" ng-click="changeLocationClick(checkin)"  class="btn btn-sm btn-success" data-toggle="modal" data-target="#changeLocation" >Change Location</button>
                            <a href="{{url('/checkouts')}}" class="btn btn-sm btn-default">Cancel</a>
                        </div>
                    </div>
                    <!--already checked out -->
                    <div class="col-md-12 col-lg-12" ng-if="checkin.length!=0 && checkin.checkout_at!=null && barcode!='' "  style="padding: 10px !important; border: 1px solid #e9e7e7; border-radius: 3px;">
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
                                <tr ng-if="checkin.is_identifier=='yes' && checkin.identifier_name2.length>0">
                                    <td>Identifier #2</td>
                                    <td>@{{checkin.identifier_name2}}: @{{checkin.identifier2}}</td>
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
                            <!--
                            <a href="{{url('/check-in-by-barcode')}}" class="btn btn-primary ">Check In</a>
                            -->
                        </div>
                    </div>
                    <span ng-if="checkinAddLoading" style="font-size: 15px; color: red;" >Please Wait...</span>
                </form>
                
                
                
                
                
                
                
                <?php if(Auth::user()->role=='admin'){ ?>
                <div class="col-lg-3 col-md-3">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-table"></i> Review Data: </a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <pre>@{{data | prettyJSON}}</pre>
                        </div>
                    </div>
                </div>
                <?php } ?>
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


<!-- Modal Location Change -->
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