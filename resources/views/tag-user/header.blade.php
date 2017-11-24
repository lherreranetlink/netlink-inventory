
        <a href="{{url('/')}}" class="logo">
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <span style="font-size: 28px; font-weight: bold;">INVENTORY</span>
        </a>
        <!-- Header Navbar: style can be found in header-->
        <!-- Sidebar toggle button-->
        <!-- Sidebar toggle button-->
        <div>
            <a href="javascript:void(0)" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button"> <i
                    class="fa fa-fw fa-bars"></i>
            </a>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <!--
                <li class="dropdown notifications-menu">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa  fa-fw fa-bell-o black"></i>
                        <span class="label label-danger">3</span>
                    </a>
                    <ul class="dropdown-menu dropdown-notifications table-striped">
                        <li>
                            <a href="" class="notification striped-col">
                                <img class="notif-image img-circle" src="{{url('img/authors/avatar7.jpg')}}" alt="avatar-image">
                                <div class="notif-body"><strong>Anderson</strong> shared post from
                                    <strong>Ipsum</strong>.
                                    <br>
                                    <small>Just Now</small>
                                </div>
                                <span class="label label-success label-mini msg-lable">New</span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification">
                                <img class="notif-image img-circle" src="img/authors/avatar6.jpg" alt="avatar-image">
                                <div class="notif-body"><strong>Williams</strong> liked <strong>Lorem Ipsum</strong>
                                    typesetting.
                                    <br>
                                    <small>5 minutes ago</small>
                                </div>
                                <span class="label label-success label-mini msg-lable">New</span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification striped-col">
                                <img class="notif-image img-circle" src="img/authors/avatar5.jpg" alt="avatar-image">
                                <div class="notif-body">
                                    <strong>Robinson</strong> started follwing <strong>Trac Theme</strong>.
                                    <br>
                                    <small>14/10/2014 1:31 pm</small>
                                </div>
                                <span class="label label-success label-mini msg-lable">New</span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification">
                                <img class="notif-image img-circle" src="img/authors/avatar1.jpg" alt="avatar-image">
                                <div class="notif-body">
                                    <strong>Franklin</strong> Liked <strong>Trending Designs</strong> Post.
                                    <br>
                                    <small>5 days ago</small>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer"><a href="javascript:void(0)">View All messages</a></li>
                    </ul>
                </li>
                -->
                
                <li class="dropdown user user-menu">
                    <a href="javascript:void(0)" class="dropdown-toggle padding-user" data-toggle="dropdown">
                        <img src="{{url('img/authors/user.jpg')}}" class="img-circle img-responsive pull-left" alt="User Image">
                        <div class="riot">
                            <div>
                                <i class="caret"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User name-->
                        <li class="user-name text-center">
                            <span>name last</span>
                        </li> 
                        <!-- Menu Body -->
                        <li class="p-t-3">
                            <a href="user_profile.html">
                                <i class="fa fa-fw fa-user"></i> Profile
                            </a>
                        </li>
                        
                        <li>
                            
                                <a href="{{ url('/tag-user/logout') }}" 
                                        >
                                        <i class="fa fa-fw fa-sign-out"></i> Logout 
                                    </a>

                                   
                            
                        </li>
                    </ul>
                </li>
            </ul>
        </div>