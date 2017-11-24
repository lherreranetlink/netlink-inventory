@extends('layout.master')

@section('title')
Single Check-In
@endsection



@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{url('angular/checkin_app.js')}}"></script>

<script>
    var mfg_url = "{{url('/manufacturers-json')}}";
    var location_url = "{{url('/locations-json')}}";
    var sublocation_url = "{{url('/sublocations-by-location-json')}}";
    var childlocation_url = "{{url('/childlocations-by-sublocation-json')}}";
    var add_checkin_url = "{{url('/checkins/add-process-ajax')}}";
    var model_url = "{{url('/models/get-models-by-mfg-ajax')}}";
    var url = "{{url('/checkins')}}";
</script>
<div class="row " style="margin-top:2px;" ng-controller="checkinCTRL">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="{{ Request::path() == 'check-in' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/check-in')}}" ><i class="fa fa-arrow-down" ></i> Single Check-In</a>
                    | <a class="{{ Request::path() == 'bulk-checkin' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/bulk-checkin')}}" ><i class="fa fa-arrow-down" ></i> Bulk Check-In</a>
                    
                    | <a class="{{ Request::path() == 'check-in-back' ? 'active_me' : '' }}" style="border-bottom: 1px dashed #FF0000;" href="{{url('/check-in-back')}}" ><i class="fa fa-refresh" ></i> Check-In Back</a>
                    
                </h3>
            </div>
            <div class="panel-body">
                <!--
                <div class="row">
                    <div class="col-lg-12" >
                        
                        <a style="text-align: center;" href=""><div style="max-width: 80px; background: #e1dddd; border: gray 1px solid; border-radius: 3px; padding: 5px">Bulk Check-In</div></a>
                    </div>
                </div>
                -->
                
                <div class="alert alert-danger" ng-show="final_result.statuscode!=200&&final_result.statuscode.length!=0">
                    <span>Error:@{{final_result.statuscode}} @{{final_result.statustext}}</span>
                    Operation Unsuccessful, Please Contact Developer!
                </div>
                <form style="padding: 20px;" class="form-horizontal col-lg-6 col-md-6" method="post" >
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Manufacturer</label><br>
                        <select style="width: 100%" 
                                ng-model="selectedMfg" 
                                ng-change="changeMfg(selectedMfg)" 
                                ng-options="mfg.name for mfg in manufacturers"
                                name="location_id" class="select2" >
                            <option value="">Select</option>
                        </select><span style="color: red;" ng-if="mfgLoading">Loading...</span>
                    </div>
                    
                    <div class="form-group">
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
                             | <a href="{{url('/models/add')}}"><i class="fa fa-plus-circle"></i> Add New</a></label>
                        
                         <?php }
                         else{
                             echo "Please Contact Admin User.";
                         }
                        ?>
                    </div>
                    
                    <div class="form-group">
                        <label>Condition</label>
                        <select ng-change="onConditionChnage()" class="form-control" ng-model="data.condition">
                            <option value="">Select</option>
                            <option value="new">New</option>
                            <option value="used">Used</option>
                            <option value="refurbished">Refurbished</option>
                        </select>
                    </div>
                    
                    <div class="form-group" ng-show="data.condition=='used'">
                        <label>Note for Used Item:</label>
                        <textarea class="form-control" ng-model="data.used_note">
                        </textarea>
                    </div>
                    <div class="form-group" ng-show="data.condition=='refurbished'">
                        <label>Note for Refurbished Item:</label>
                        <textarea class="form-control" ng-model="data.refurbished_note">
                        </textarea>
                    </div>
                    
                    
                    <div class="form-group" ng-show="selectedModel.is_mac=='yes'">
                        <label>MAC Address</label>
                        <input type="text" ng-model="data.mac" name="model_number" class="form-control" required="" />
                    </div>
                    
                    <div class="form-group" ng-show="selectedModel.identifier=='yes'">
                        <label>@{{selectedModel.identifier_name}}</label>
                        <input type="text" ng-model="data.identifier" class="form-control" name="" required="" placeholder="First Indetifier" />
                        <div ng-if="selectedModel.identifier_name2.length>0">
                            <label>@{{selectedModel.identifier_name2}}</label>
                            <input type="text" ng-model="data.identifier2" class="form-control" name="" placeholder="Second Identifier" />
                        </div>
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
                    
                    <input type="button" ng-click="addCheckin()" class="btn btn-sm btn-success" value="Check-In" />
                    <a href="{{url('/checkins')}}" class="btn btn-sm btn-default">Cancel</a>
                    
                    <span ng-if="checkinAddLoading" style="font-size: 15px; color: red;" >Please Wait...</span>
                </form>
                
                
                <div class="col-lg-6 col-md-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-table"></i> Selected Model: </a>
                            </h3>
                        </div>
                        <div class="panel-body" ng-if="selected_model.length!=0">
                            <center>
                                <div class="col-md-12" style="text-align: center;">
                                    <img   ng-if="selected_model.picture.length!==0||selected_model.length>10" data-ng-src="@{{selected_model.picture}}" width="110" class="thumbnail img-responsive" />
                                </div>
                            </center>
                            <table class="table table-striped table-responsive">
                                <tr>
                                    <td colspan="2" align="center"></td>
                                </tr>
                                <tr>
                                    <td>Model:</td>
                                    <td>@{{selected_model.model_number}}</td>
                                </tr>
                                <tr>
                                    <td>Manufacturer</td>
                                    <td>@{{selectedMfg.name}}</td>
                                </tr>
                            </table>
                            
                        </div>
                        <div class="panel-body" ng-if="selected_model.length==0">
                            <label style="color: #0880d7;">No model has been selected yet.</label>
                        </div>
                    </div>
                    <?php if(Auth::user()->role=='admin'){ ?>
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-table"></i> Check-In Data: </a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <pre>@{{data | prettyJSON}}</pre>
                        </div>
                    </div>
                    <?php } ?>
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