@extends('layout.master')

@section('title')
{{$data['title']}}: <u style="font-weight: bold;">User</u>
@endsection



@section('content')
<div class="row " style="margin-top:2px;">
                    <div class="col-lg-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-fw fa-table"></i> Customer: {{$data['user']->name}} </a>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-4">
                                    <form class="form-horizontal" method="post" action="{{url('/users/edit')}}">
                                    {{ csrf_field() }}
                                    
                                        <input type="hidden" name="id" value="{{$data['user']->id}}" />
                                    
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="{{$data['user']->name}}" class="form-control" required="" />
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" value="{{$data['user']->email}}" class="form-control"  />
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="text" name="password" value="{{$data['user']->password}}" class="form-control"  />
                                    </div>
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select>
                                            <option>User</option>
                                            <option>Admin</option>
                                        </select>
                                    </div>
                                        
                                    
                                 
                                    <input type="submit" class="btn btn-sm btn-success" value="Update" />
                                    <a href="{{url('/users')}}" class="btn btn-sm btn-default">Cancel</a>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                


@endsection