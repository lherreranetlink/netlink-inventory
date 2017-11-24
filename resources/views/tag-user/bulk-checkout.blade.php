@extends('tag-user.master')

@section('title')
{{$data['title']}}: <u style="font-weight: bold;">Bulk Check-Out</u>
@endsection



@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{url('angular/bulk_checkout_app.js')}}"></script>

<script>
    var add_bulk_checkout_url = "{{url('/checkouts/add-bulk-process-ajax')}}";
    var change_bulk_location_url = "{{url('/checkouts/change-bulk-location-process-ajax')}}";
    var mfg_url = "{{url('/manufacturers-json')}}";
    var category_url = "{{url('/categories-json')}}";
    var location_url = "{{url('/locations-json')}}";
    var sublocation_url = "{{url('/sublocations-by-location-json')}}";
    var childlocation_url = "{{url('/childlocations-by-sublocation-json')}}";
    var url = "{{url('/tag-user/home')}}";
    var location_redirect_url = "{{url('/tag-user/home')}}";
    var customer_url = "{{url('/customers-json')}}";
    var get_checkins_by_barcode_url = "{{url('/get-checkin-barcode-ajax')}}";
</script>
<div class="row " style="margin-top:2px;" ng-controller="bulkCheckoutCTRL">
    <div class="col-lg-12 col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Buld Checkout
                    <!--
                    <a class="{{ Request::path() == 'check-out' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/check-out')}}" ><i class="fa fa-arrow-up" ></i> Single Check-Out</a>
                    | <a class="{{ Request::path() == 'bulk-checkout' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/bulk-checkout')}}" ><i class="fa fa-arrow-up" ></i> Bulk Check-Out</a>
                    -->
                </h3>
            </div>
            <div class="panel-body">
                <div class="alert alert-danger" ng-show="final_result.statuscode!=200&&final_result.statuscode.length!=0">
                    <span>Error:@{{final_result.statuscode}} @{{final_result.statustext}}</span>
                    Operation Unsuccessful, Please Contact Developer!
                </div>
                <form style="padding: 20px;" class="form-horizontal col-lg-7 col-md-7" method="post" >
                    {{ csrf_field() }}
                    <div class="form-group">
                        <lable>Scan Barcode</lable>
                        <input autofocus="true" class="form-control barcode-input" style="background: #d9f1d9" ng-model="barcode" ng-keyup="$event.keyCode == 13 && getProductDetail()" placeholder="Please Scan Barcode and Press Enter"  />   
                        <div style="color: red; font-size: 16px;" ng-if="processingDelay">Please Wait...</div>
                    </div>
                    
                    <span style="color: red;" ng-if="alreadyInList.length!=0">@{{alreadyInList}}</span>
                    <span style="color: red;" ng-if="recordNotFount">Record Not found for @{{barcode}}</span>
                    <div ng-if="checkins.length==0">
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
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="col-md-12" style="padding: 10px !important; border: 1px solid #e9e7e7; border-radius: 3px;" ng-show="checkins.length!=0" >
                        <table style="width: 100%;">
                            <tr ng-repeat="checkin in checkins" style="margin-top: 2px;">
                                <td colspan="2" style="padding: 3px; border: 1px solid silver;">
                                    <div style="color: @{{checkin.valid_color}}">
                                        <b>@{{$index + 1}}) @{{checkin.manufacturer}}  
                                            @{{checkin.model_number}}</b> 
                                        <div style="font-size: 12px;">
                                        <span ng-if="checkin.mac.length!=0">MAC:@{{checkin.mac}}</span> | 
                                        <span ng-if="checkin.identifier.length!=0">@{{checkin.identifier_name}}:@{{checkin.identifier}}</span>
                                        
                                        <i style="font-weight: bold; color: green;" ng-if="checkin.valid" class="fa fa-check"></i>
                                        <i style="font-weight: bold; color: red;" ng-if="checkin.valid==false" class="fa fa-close"></i> 
                                        | <span>@{{checkin.msg}}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <button  type="button" ng-click="checkOutModal()"  class="btn btn-sm btn-primary" data-toggle="modal" data-target="#checkOut" >Check-Out</button>
                        <button type="button" ng-click="changeLocationClick()"  class="btn btn-sm btn-success" data-toggle="modal" data-target="#changeLocation" >Change Location</button>
                    </div>
                    <span ng-if="checkinAddLoading" style="font-size: 15px; color: red;" >Please Wait...</span>
                </form>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                <?php if(Auth::user()->role=='admin'){ ?>
                <div class="col-lg-5 col-md-5">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-table"></i> Scanned Barcodes: </a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <pre>@{{scanned_barcode | prettyJSON}} @{{inout_ids}}</pre>
                        </div>
                    </div>
                    
                    
                </div>
                <?php } ?>
                
            </div>
        </div>
    </div>
  

<!-- Modal checkout for customer -->
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
          <div style="padding: 10px !important; border: 1px solid #e9e7e7; border-radius: 3px;">
              <div ng-if="valid_checkouts.length!=0">
                <h5>Your Valid Checkouts for the customer would be following</h5>
                <div  ng-repeat="checkin in valid_checkouts" style="background: #f6f5f5; border: 1px solid #e7e6e6; margin: 2px; padding: 5px;">
                    <span style="font-weight: bold;">
                        @{{$index + 1}}) @{{checkin.manufacturer}}  
                        @{{checkin.model_number}} | @{{checkin.category}}
                    </span>
                    <div style="font-size: 11px;">
                        <span ng-if="checkin.mac.length!=0">MAC:@{{checkin.mac}}</span>
                        <span ng-if="checkin.identifier.length!=0"> | @{{checkin.identifier_name}}:@{{checkin.identifier}}</span>
                      <br><span ng-if="checkin.location.length!=0">
                          Cur. Location: @{{checkin.location}}
                          <span ng-if="checkin.sublocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.sublocation}}</span>
                          <span ng-if="checkin.childlocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.childlocation}}</span>
                      </span>
                    </div>
                </div>
              </div >
              <div ng-if="valid_checkouts.length==0">
                  <h5 style="color:red">No valid Item entered to checkout or for location change!</h5>
              </div>
          </div>
          
          
          <div class="form-group" ng-show="customerLoading==false">
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
          <button ng-disabled="valid_checkouts.length==0 || selectedCustomer.length==0" type="button" class="btn btn-success" ng-click="addCheckout()" >Check Out <i class="fa fa-check"></i></button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="cancelCheckOut()" >Cancel <i class="fa fa-close"></i></button>
      </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div id="changeLocation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Location</h4>
      </div>
      <div class="modal-body">
          <div style="padding: 10px !important; border: 1px solid #e9e7e7; border-radius: 3px;">
              <div ng-if="valid_checkouts.length!=0">
                <h5>Your Valid Checkouts for the customer would be following</h5>
                <div  ng-repeat="checkin in valid_checkouts" style="background: #f6f5f5; border: 1px solid #e7e6e6; margin: 2px; padding: 5px;">
                    <span style="font-weight: bold;">
                        @{{$index + 1}}) @{{checkin.manufacturer}}  
                        @{{checkin.model_number}} | @{{checkin.category}}
                    </span>
                    <div style="font-size: 11px;">
                        <span ng-if="checkin.mac.length!=0">MAC:@{{checkin.mac}}</span>
                        <span ng-if="checkin.identifier.length!=0"> | @{{checkin.identifier_name}}:@{{checkin.identifier}}</span>
                      <br><span ng-if="checkin.location.length!=0">
                          Cur. Location: @{{checkin.location}}
                          <span ng-if="checkin.sublocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.sublocation}}</span>
                          <span ng-if="checkin.childlocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.childlocation}}</span>
                      </span>
                    </div>
                </div>
              </div >
              <div ng-if="valid_checkouts.length==0">
                  <h5 style="color:red">No valid Item entered to checkout or for location change!</h5>
              </div>
          </div>
          
        <div class="form-group">
            <label>Location</label> <br>
            <select style="width: 100%" 
                    ng-model="selectedLocation" 
                    ng-change="locationChange(selectedLocation)" 
                    ng-options="loc.name for loc in locations"
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
        <button ng-disabled="valid_checkouts.length==0 || selectedLocation.length==0" type="button" class="btn btn-success" ng-click="changeLocationProcess()" >Change Location <i class="fa fa-check"></i></button>
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