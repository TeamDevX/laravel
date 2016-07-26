<div id="header"><!--header-->                  
    <div class="logo pull-left">
        <a href="{{url('')}}"><img src="{{asset('images/logo.png')}}" alt="" /></a>
    </div>     
    <div class="header-right">           
        <div class="shop-menu pull-right">
            <ul class="nav navbar-nav">
                <a href="{{url('myaccount/edit')}}"><i class="fa fa-user"></i> {{Auth::check() ? 'Edit Profile' : ''}}</a>
                <li><a href="{{Auth::check() ? url('/auth/logout') : url('/auth/login')}}"><i class="fa fa-lock"></i> {{Auth::check() ? 'Logout' : 'Login'}}</a></li>
            </ul>
        </div>
    </div>                       
</div><!--/header-->