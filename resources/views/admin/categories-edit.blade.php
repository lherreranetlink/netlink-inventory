@extends('layout.master')

@section('title')
{{$data['title']}}
@endsection



@section('content')
<div class="row " style="margin-top:2px;">
                    <div class="col-lg-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-fw fa-table"></i> Customer: {{$data['category']->name}} </a>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-4">
                                    <form class="form-horizontal" method="post" action="{{url('/categories/edit')}}">
                                    {{ csrf_field() }}
                                    
                                        <input type="hidden" name="id" value="{{$data['category']->id}}" />
                                    
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="{{$data['category']->name}}" class="form-control" required="" />
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Status</label>
                                        
                                        <select name="deleted" class="form-control" >
                                            <option <?php if($data['category']->deleted==1){echo 'selected';} ?> value="1">Deleted</option>
                                            <option <?php if($data['category']->deleted==0){echo 'selected';} ?> value="0">Active</option>
                                        </select>
                                    </div>
                                    
                                 
                                    <input type="submit" class="btn btn-sm btn-success" value="Update" />
                                    <a href="{{url('/categories')}}" class="btn btn-sm btn-default">Cancel</a>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                


@endsection