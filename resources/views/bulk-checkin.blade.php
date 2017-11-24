@extends('layout.master')

@section('title')
Bulk Check-In
@endsection



@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{url('angular/bulk_checkin_app.js')}}"></script>

<script>
    var mfg_url = "{{url('/manufacturers-json')}}";
    var location_url = "{{url('/locations-json')}}";
    var sublocation_url = "{{url('/sublocations-by-location-json')}}";
    var childlocation_url = "{{url('/childlocations-by-sublocation-json')}}";
    var add_bulk_checkin_url = "{{url('/checkins/add-bulk-process-ajax')}}";
    var model_url = "{{url('/models/get-models-by-mfg-ajax')}}";
    var url = "{{url('/checkins')}}";
</script>
<div class="row " style="margin-top:2px;" ng-controller="bulkCheckinCTRL">
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
                <form style="padding: 20px;" class="form-horizontal col-lg-7 col-md-7" method="post" >
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label style="font-weight: bold;">No. of Items <small style="color: gray; font-weight: normal;">(Total Number of Item that you wanna check in)</small></label><br>
                        <input type="number" class="form-control" style="max-width: 120px" ng-model="data.number_checkins" min="2" />
                    </div>
                    <div class="form-group">
                        <label>Manufacturer</label><br>
                        <select style="width: 100%"
                                ng-model="selectedMfg"
                                ng-change="changeMfg(selectedMfg)"
                                ng-options="mfg.name for mfg in manufacturers"
                                name="location_id" class="select2">
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
                             | <a href="#"><i class="fa fa-plus-circle"></i> Add New</a></label>

                         <?php }
                         else{
                             echo "Please Contact Admin User.";
                         }
                        ?>
                    </div>

                    <div class="form-group">
                        <label>Condition</label>
                        <select class="form-control" ng-model="data.condition">
                            <option value="">Select</option>
                            <option value="new">New</option>
                            <option value="used">Used</option>
                            <option value="refurbished">Refurbished</option>
                        </select>
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

                    <div class="form-group" ng-show="selectedModel.is_mac=='yes' || selectedModel.identifier=='yes'">
                        <table >
                            <tr ng-repeat="i in getNumber(data.number_checkins) track by $index">
                                <td ng-show="selectedModel.is_mac=='yes'">
                                    <label>MAC Address</label>
                                    <input type="text" ng-model="data.mac[$index]" name="" class="form-control" required="" />
                                </td>
                                <td ng-show="selectedModel.identifier=='yes'">
                                    <label>@{{selectedModel.identifier_name}}</label>
                                    <input type="text" ng-model="data.identifier[$index]" name="" class="form-control" required="" />
                                </td>
                                <td ng-show="selectedModel.identifier=='yes' && selectedModel.identifier_name2.length>0">
                                    <label>@{{selectedModel.identifier_name2}}</label>
                                    <input type="text" ng-model="data.identifier2[$index]" name="" class="form-control" />
                                </td>
                            </tr>

                        </table>

                    </div>



                    <input type="button" ng-click="addCheckin()" class="btn btn-sm btn-success" value="Check-In" />
                    <a href="{{url('/checkins')}}" class="btn btn-sm btn-default">Cancel</a>

                    <span ng-if="checkinAddLoading" style="font-size: 15px; color: red;" >Please Wait...</span>
                </form>
                <?php if(Auth::user()->role=='admin'){ ?>
                <!--Test Code -->
                <div class="col-lg-5 col-md-5">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-table"></i> Load data from spreadsheet: </a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div>
                                <label for="check-in-spreadsheet">Choose file to upload</label>
                                <input id="check-in-spreadsheet" type="file"
                                       accept=".xlsx">
                                <input type="button" ng-click = "uploadTempFile()" class="btn btn-sm btn-success" value="Upload">
                            </div>
                        </div>
                    </div>
                    <!-- -->
                </div>
                <div class="col-lg-5 col-md-5">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-table"></i> Available Models: </a>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div>
                                <b>Applied Filter: </b>
                                <span ng-if="data.manufacturer_id!==''">Manufacturer</span>
                                <span ng-if="data.model_id!==''"> | Model Name</span>

                            </div>
                            <pre>@{{available_models | prettyJSON}}</pre>
                        </div>
                    </div>
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
                </div>
                <?php } ?>
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