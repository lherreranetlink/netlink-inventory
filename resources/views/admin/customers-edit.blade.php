@extends('layout.master')

@section('title')
{{$data['title']}}: <u style="font-weight: bold;">Customers</u>
@endsection



@section('content')
<div class="row " style="margin-top:2px;">
                    <div class="col-lg-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-fw fa-table"></i> Customer: {{$data['customer']->name}} </a>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-4">
                                    <form class="form-horizontal" method="post" action="{{url('/customers/edit')}}">
                                    {{ csrf_field() }}
                                    
                                        <input type="hidden" name="id" value="{{$data['customer']->id}}" />
                                    
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="{{$data['customer']->name}}" class="form-control" required="" />
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="address" value="{{$data['customer']->address}}" class="form-control" />
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="text" name="contact" value="{{$data['customer']->contact}}" class="form-control"  />
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Status</label>

                                        <select name="deleted" class="form-control" >
                                            <option <?php if($data['customer']->deleted==1){echo 'selected';} ?> value="1">Deleted</option>
                                            <option <?php if($data['customer']->deleted==0){echo 'selected';} ?> value="0">Active</option>
                                        </select>
                                    </div>
                                   
                                 
                                    <input type="submit" class="btn btn-sm btn-success" value="Update" />
                                    <a href="{{url('/customers')}}" class="btn btn-sm btn-default">Cancel</a>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                


@endsection