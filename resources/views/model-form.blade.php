@extends('layout.master')

@section('title')
{{$data['title']}}: Add New Model
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
<script type="text/javascript" src="{{url('angular/model_app.js')}}"></script>

<script>
    var mfg_url = "{{url('/manufacturers-json')}}";
    var category_url = "{{url('/categories-json')}}";
    var subcategory_url = "{{url('/subcategories-by-category-json')}}";
    var childcategory_url = "{{url('/childcategories-by-subcategory-json')}}";
    var add_model_url = "{{url('/models/add-process-ajax')}}";
    var url = "{{url('/models')}}";
</script>
<div class="row " style="margin-top:2px;" ng-controller="modelCTRL">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-table"></i> Model <small>Add new model</small> </a>
                </h3>
            </div>
            <div class="panel-body">
                <form style="padding: 20px;" class="form-horizontal col-lg-7 col-md-7" method="post" >
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Manufacturer</label><br>
                        <select style="width: 100%" ng-model="data.manufacturer_id" name="manufacturer_id" class="select2">
                            <option value="">Select</option>
                            <option value="@{{mfg.id}}" ng-repeat="mfg in manufacturers track by $index">@{{mfg.name}}</option>
                        </select><span style="color: red;" ng-if="mfgLoading">Loading...</span>
                    </div>
                    
                    
                    
                    <div class="form-group">
                        <label>Model Name/Number <small>(e.g. RB2011)</small></label>
                        <input type="text" ng-model="data.model_number" name="model_number" class="form-control" required="" />
                    </div>
                    
                    <div class="form-group">
                        <label>Low Stock Notification Level<small> (No. of Items that)</small></label>
                        <input type="number" ng-model="data.low_stock" name="low_stock" class="form-control" required="" placeholder="10" />
                    </div>
                    
                    <div class="form-group">
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
                    <div class="form-group" ng-show="is_subcategory">
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
                    <div class="form-group" ng-show="is_childcategory">
                        <label>Child Category</label> <br>
                        <select style="width: 100%" 
                                ng-model="selectedChildCategory" 
                                ng-change="childcategoryChange(selectedChildCategory)" 
                                ng-options="childcat.name for childcat in childcategories"
                                name="childcategory_id" class="select2">
                            <option value="">Select</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label><b>MAC Address? </b></label>
                        <input  type="radio" name="is_mac" ng-model="data.is_mac"  value="yes" />: Yes 
                        <input  type="radio" name="is_mac" ng-model="data.is_mac"  value="no"  checked="" />: No
                        
                    </div>
                    <div class="form-group">
                        <label><b>Identifier ? </b></label>
                        <input  type="radio" name="identifier" ng-model="data.identifier"  value="yes" />: Yes 
                        <input  type="radio" name="identifier" ng-model="data.identifier"  value="no"  checked="" />: No
                        <small style="color: gray;">(Name-Value Pair will be required)</small>
                        <table ng-if="data.identifier=='yes'">
                            <tr>
                                <td><input ng-model="data.identifier_name"  type="text" name="identifier_name" placeholder="Identifier Name #1" required="" />
                                    <small style="color: gray;">Identifier1 : Default: 'Serial #'</small>
                                </td>
                            </tr>
                            <tr>
                                <td><input ng-model="data.identifier_name2"  type="text" name="identifier_name2" placeholder="Identifier Name #2"  />
                                    <small style="color: gray;">Identifier2 : Leave Blank, If not applicable</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea ng-model="data.description" name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <style>
                            .cropArea {
                                background: #E4E4E4;
                                overflow: hidden;
                                width:500px;
                                height:350px;
                            }
                        </style>
                        <label>Picture</label>
                        <div>Select an image file: <input type="file" id="fileInput" /></div>
                        <div class="cropArea responsive img-responsive">
                          <img-crop area-type="square" image="myImage" result-image="myCroppedImage"></img-crop>
                        </div>
                    </div>
                    
                    <input type="button" ng-click="addModel()" class="btn btn-sm btn-success" value="Add Model" />
                    <a href="{{url('/models')}}" class="btn btn-sm btn-default">Cancel</a>
                    
                    <span ng-if="modelAddLoading" style="font-size: 15px; color: red;" >Please Wait...</span>
                </form>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                <?php if(Auth::user()->role=="admin"){ ?>
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
                                <span ng-if="data.model_number!==''"> | Model Name</span>
                                <span ng-if="data.category_id!==''"> | Category</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-table"></i> MOdel Data: </a>
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
    
    
    
    <!-- PROFILE PIC MODAL------------------------>
    
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