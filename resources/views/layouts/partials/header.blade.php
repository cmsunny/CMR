 <!-- main header @s -->
 <div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
           
            
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-md-block">
                                    
                                    
                                    <div class="user-status">{{ Auth::user()->name }}</div> 
                                    <div class="user-name dropdown-indicator">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <span>AB</span>
                                    </div>
                                    
                                {{-- <div class="media-body">
                                    <h6 class="pro-user-name mt-0 mb-0">{{auth()->user()->user_name ?? ucwords(auth()->user()->name)}}</h6>
                                    <span class="pro-user-desc">{{ucwords(getUserRole(auth()->user()) ? getUserRole(auth()->user())->title : null)}}</span>
                                </div> --}}
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->name }}</span>
                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="html/user-profile-regular.html"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                    <li><a href="html/user-profile-setting.html"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                                    <li><a href="html/user-profile-activity.html"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>
                                    <li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>
                                </ul>
                            </div> --}}
                            <div class="dropdown-inner">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                                        <em class="icon ni ni-signout"></em>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                                {{-- <ul class="link-list">
                                    <li><a href="#"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                </ul> --}}
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                    
                </ul><!-- .nk-quick-nav -->
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
<!-- main header @e -->