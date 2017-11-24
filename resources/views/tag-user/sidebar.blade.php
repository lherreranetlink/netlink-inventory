
            <div id="menu" role="navigation">
                <div class="nav_profile">
                    <div class="media profile-left">
                        <a class="pull-left profile-thumb" href="javascript:void(0)">
                            <img src="{{url('img/authors/user.jpg')}}" class="img-circle" alt="User Image">
                        </a>
                        <div class="content-profile">
                            <h4 class="media-heading">
                                {{session('user-name')}}
                            </h4>
                            <p>{{session('user-role')}}</p>
                        </div>
                    </div>
                </div>
                <ul class="navigation">
                    <li class="{{ Request::path() == 'tag-user/home' ? 'active' : '' }}">
                        <a href="{{url('/tag-user/home')}}">
                            <i class="menu-icon fa fa-fw fa-home"></i>
                            <span class="mm-text ">Home</span>
                        </a>
                    </li>
                    
                </ul>
                <!-- / .navigation -->
            </div>
            <!-- menu -->