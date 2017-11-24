@extends('layout.master')

@section('title')
{{$data['title']}}: <u style="font-weight: bold;">Manufacturer</u>
@endsection



@section('content')


                <div class="row ">
                    <div class="col-md-12 col-sm-6">
                        <div class="panel panel-border">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="dashboard" data-size="20" data-loop="true" data-c="#F89A14" data-hc="#F89A14"></i>
                                    Manufacturer 
                                    <small>- edit</small>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-4">
                                    <form class="form-horizontal" method="post" action="{{url('/manufacturers/edit')}}">
                                    {{ csrf_field() }}
                                    
                                        <input type="hidden" name="id" value="{{$data['manufacturer']->id}}" />
                                    
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="{{$data['manufacturer']->name}}" class="form-control" required="" />
                                    </div>
                                    
                                     <div class="form-group">
                                        <label>Status</label>
                                        
                                        <select name="deleted" class="form-control" >
                                            <option <?php if($data['manufacturer']->deleted==1){echo 'selected';} ?> value="1">Deleted</option>
                                            <option <?php if($data['manufacturer']->deleted==0){echo 'selected';} ?> value="0">Active</option>
                                        </select>
                                    </div>
                                 
                                    <input type="submit" class="btn btn-sm btn-success" value="Update" />
                                    <a href="{{url('/manufacturers')}}" class="btn btn-sm btn-default">Cancel</a>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>


@endsection