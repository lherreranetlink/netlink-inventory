
            <div id="menu" role="navigation">
                <div class="nav_profile">
                    <div class="media profile-left">
                        <a class="pull-left profile-thumb" href="{{url('/profile')}}">
                            <img src="<?php 
                                if(Auth::user()->profile_pic!=""){
                                    echo Auth::user()->profile_pic;
                                }
                                else{
                                    echo url('profile_pics/user-profile.png');
                                }
                                ?>" class="img-circle" alt="User Image">
                        </a>
                        <div class="content-profile">
                            <h4 class="media-heading">
                                <a href="{{url('/profile')}}">
                                    {{Auth::user()->name}}
                                </a>
                            </h4>
                            <p>{{ucwords(Auth::user()->role)}}</p>
                        </div>
                    </div>
                </div>
                <ul class="navigation">
                    <li class="{{ Request::path() == 'home' ? 'active' : '' }}">
                        <a href="{{url('home')}}">
                            <i class="menu-icon fa fa-fw fa-dashboard"></i>
                            <span class="mm-text ">Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ Request::path() == 'check-in' ? 'active' : '' }}">
                        <a href="{{url('check-in')}}">
                            <i class="menu-icon fa fa-fw fa-plus-square"></i>
                            <span class="mm-text ">Check-In</span>
                        </a>
                    </li>
                    <li class="{{ Request::path() == 'check-out' ? 'active' : '' }}">
                        <a href="{{url('check-out')}}">
                            <i class="menu-icon fa fa-fw fa-minus-square"></i>
                            <span class="mm-text ">Check-Out</span>
                        </a>
                    </li>
                    <!--
                    <li class="{{ Request::path() == 'profile' ? 'active' : '' }}">
                        <a href="{{url('profile')}}">
                            <i class="menu-icon fa fa-fw fa-minus-square"></i>
                            <span class="mm-text ">Profile</span>
                        </a>
                    </li>
                    -->
                    <li class="{{ Request::path() == 'stock' ? 'active' : '' }}">
                        <a href="{{url('stock')}}">
                            <i class="menu-icon fa fa-fw fa-download"></i>
                            <span class="mm-text ">Stock</span>
                        </a>
                    </li>
                    <li class="{{ Request::path() == 'checkins' || Request::path() == 'checkouts' ? 'active' : '' }}">
                        <a href="{{url('/checkins')}}">
                            <i class="menu-icon fa fa-fw fa-list"></i>
                            <span class="mm-text ">Logs</span>
                        </a>
                    </li>
                    
                    <li class="menu-dropdown <?php if(Request::path() == 'categories'||
                            Request::path() == 'subcategories'||
                            Request::path() == 'childcategories/edit'||
                            Request::path() == 'subcategories/edit'||
                            Request::path() == 'categories/edit'||
                            Request::path() == 'childcategories'||
                            
                            Request::path() == 'sublocations'||
                            Request::path() == 'childlocations/edit'||
                            Request::path() == 'sublocations/edit'||
                            Request::path() == 'locations/edit'||
                            Request::path() == 'customers/edit'||
                            Request::path() == 'models/edit'||
                            Request::path() == 'childlocations'||
                            Request::path() == 'models/add'||
                            
                            Request::path() == 'locations'||
                            Request::path() == 'manufacturers'||
                            Request::path() == 'manufacturers/edit'||
                            Request::path() == 'customers'||
                            Request::path() == 'models'||
                            Request::path() == 'users'){ echo 'active';} ?>">
                        
                        <a href="#">
                            <i class="menu-icon fa fa-check-square"></i>
                            <span>Admin</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="<?php if(Request::path() == 'categories' ||
                                    Request::path() == 'subcategories'||
                                    Request::path() == 'childcategories/edit'||
                                    Request::path() == 'subcategories/edit'||
                                    Request::path() == 'categories/edit'||
                                    Request::path() == 'childcategories'){echo "active";} ?>">
                                <a href="{{url('categories')}}">
                                    <i class="fa fa-fw fa-fire"></i> Categories
                                </a>
                            </li>
                            <li class="<?php if(Request::path() == 'locations' ||
                                    Request::path() == 'sublocations'||
                                    Request::path() == 'childlocations/edit'||
                                    Request::path() == 'sublocations/edit'||
                                    Request::path() == 'locations/edit'||
                                    Request::path() == 'childlocations'){echo "active";} ?>">
                                <a href="{{url('locations')}}">
                                    <i class="fa fa-fw fa-map-marker"></i> Locations
                                </a>
                            </li>
                            <li class="<?php if(Request::path() == 'manufacturers/edit' ||
                                    Request::path() == 'manufacturers'){echo "active";} ?>">
                                <a href="{{url('manufacturers')}}">
                                    <i class="fa fa-fw fa-building-o"></i> Manufacturers 
                       
                                </a>
                            </li>
                            <li class="<?php if(Request::path() == 'customers/edit' ||
                                    Request::path() == 'customers'){echo "active";} ?>">
                                <a href="{{url('customers')}}">
                                    <i class="fa fa-fw fa-users"></i> Customers 
                                </a>
                            </li>
                            <li class="<?php if(Request::path() == 'models/edit' ||
                                    Request::path() == 'models/add' ||
                                    Request::path() == 'models'){echo "active";} ?>">
                                <a href="{{url('models')}}">
                                    <i class="fa fa-fw fa-star"></i> Models 
                                </a>
                            </li>
                            <li class="<?php if(
                                    Request::path() == 'users'){echo "active";} ?>">
                                <a href="{{url('users')}}">
                                    <i class="fa fa-fw fa-user"></i> User 
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    
                    
                </ul>
                <!-- / .navigation -->
            </div>
            <!-- menu -->