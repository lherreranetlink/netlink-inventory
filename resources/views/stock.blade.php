@extends('layout.master')

@section('title')
Stock
@endsection



@section('content')
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/datatables/css/buttons.bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.dataTables.css')}}">
<!-- end of global css -->
<link rel="stylesheet" type="text/css" href="{{url('css/pages/datatables.css')}}">
<script src="{{url('vendors/select2/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{url('angular/stock_app.js')}}"></script>

<script>
    var mfg_url = "{{url('/manufacturers-json')}}";
    var category_url = "{{url('/categories-json')}}";
    var subcategory_url = "{{url('/subcategories-by-category-json')}}";
    var childcategory_url = "{{url('/childcategories-by-subcategory-json')}}";
    var search_url = "{{url('/stock/search-ajax')}}";
    //var keyup_search_url = "{{url('/stock/keyup-search-ajax')}}";
    
    var url = "{{url('/stock')}}";
</script>
<div class="row " style="margin-top:2px;" ng-controller="stockCTRL">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-table"></i> Available in Stock
                </h3>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="form-group col-md-3">
                        <label>Manufacturer</label><br>
                        <select style="width: 100%" ng-model="data.manufacturer_id" name="manufacturer_id" class="select2">
                            <option value="">Select</option>
                            <option value="@{{mfg.id}}" ng-repeat="mfg in manufacturers track by $index">@{{mfg.name}}</option>
                        </select><span style="color: red;" ng-if="mfgLoading">Loading...</span>
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label>Category</label> <br>
                        <select style="width: 100%" 
                                ng-model="selectedCategory" 
                                ng-change="categoryChange(selectedCategory)" 
                                ng-options="cat.name for cat in categories"
                                name="category_id" class="select2">
                            <option value="">Select</option>
                            
                        </select><span style="color: red;" ng-if="catLoading">Loading...</span>
                    </div>
                    
                    <span style="color: red;" ng-if="subLoading">Loading...</span>
                    <div class="form-group col-md-3" ng-show="is_subcategory" >
                        <label>Sub Category</label> <br>
                        <select style="width: 100%" 
                                ng-model="selectedSubCategory" 
                                ng-change="subcategoryChange(selectedSubCategory)" 
                                ng-options="subcat.name for subcat in subcategories"
                                name="subcategory_id" class="select2">
                            <option value="">Select</option>
                        </select>
                        
                    </div>
                    <span style="color: red;" ng-if="childLoading">Loading...</span>
                    <div class="form-group col-md-3" ng-show="is_childcategory" >
                        <label>Child Category</label> <br>
                        <select style="width: 100%" 
                                ng-model="selectedChildCategory" 
                                ng-change="childcategoryChange(selectedChildCategory)" 
                                ng-options="childcat.name for childcat in childcategories"
                                name="childcategory_id" class="select2">
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-12">
                        <label>Keyword</label> <br>
                        <input type="text" class="form-control" ng-model="data.keyword" ng-keyup="$event.keyCode == 13 ? searchBtnClick() : searchByKeyUp()" placeholder="e.g. RB2011" />
                        <button style="margin-top: 10px;" ng-click="searchBtnClick()" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Search</button>
                        <button style="margin-top: 10px;" class="btn btn-danger btn-sm" ng-click="resetBtnClick()"> <i class="fa fa-refresh"></i> Reset</button>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <span style="color: red; font-weight: bold;" ng-if="searching">Please wait...Searching...</span>
                    <div style="color: green; font-size: 17px; padding: 5px;">
                        Total Available: <label class="label label-success">@{{search_result.content.length}}</label> <small>(These are available in stock for checkout)</small>
                    </div>
                    <table style="font-size: 12px" class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th ng-click="sort('checkin_at')">Checked-In
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='checkin_at'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th ng-click="sort('model_number')">Model
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='model_number'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th ng-click="sort('location')">Cur. Location
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='location'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th ng-click="sort('mac')">MAC
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='mac'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th ng-click="sort('identifier')">Identifier
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='identifier'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>  
                            </tr>
                        </thead>
                        <tbody>
                            <tr dir-paginate="checkin in search_result.content | orderBy:sortKey:reverse | filter:search | itemsPerPage:10">
                                <td >@{{checkin.checkin_at | cmdate:'MMM, dd yyyy hh:mm' }} by<br>@{{checkin.checkin_by}}</td>
                                <td ><span style="color: #0099cc">@{{checkin.manufacturer}} <b>@{{checkin.model_number}}</b> | @{{checkin.category}} | @{{checkin.subcategory}}</span>
                                    <br>
                                    <a href="" data-toggle="modal" data-target="@{{'#myModal_'+checkin.mac}}">
                                        <img ng-if="checkin.picture.length!==0||checkin.length>10" data-ng-src="@{{checkin.picture}}" width="50" class="thumbnail img-responsive" />
                                        <i ng-if="checkin.picture.length==0">Picture Not Available <br></i>
                                    </a>
                                    <!-- Modal -->
                                    <div ng-attr-id="@{{'myModal_'+checkin.mac}}" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Detail</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row" >
                                                <div class="col-md-12">
                                                    <img ng-if="checkin.picture.length!==0||checkin.length>10" data-ng-src="@{{checkin.picture}}" width="340" class="thumbnail img-responsive" />
                                                    <p>@{{checkin.description}}</p>
                                                </div>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                          </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>    
                                    <span style="font-weight: bold;">
                                        Condition: 
                                        <label class="label label-success" ng-if="checkin.condition=='new'">@{{checkin.condition}}</label>
                                        <label class="label label-warning" ng-if="checkin.condition!='new'">@{{checkin.condition}}</label>
                                    </span>
                                </td>
                                <td >
                                    <span ng-if="checkin.location.length!=0" style="color: darkgreen;">@{{checkin.location}}</span><br>
                                    <span ng-if="checkin.sublocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.sublocation}}</span><br>
                                    <span style="font-weight: bold; color: black;" ng-if="checkin.childlocation.length!=0"><i class="fa fa-arrow-right"></i> @{{checkin.childlocation}}</span>
                                </td>
                                <td >@{{checkin.mac}}</td>
                                <td >
                                    <span ng-if="checkin.is_identifier=='yes'">@{{checkin.identifier_name}}:</span>
                                    @{{checkin.identifier}}
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                    
                    <dir-pagination-controls max-size="10" direction-links="true" boundary-links="true" ></dir-pagination-controls>
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