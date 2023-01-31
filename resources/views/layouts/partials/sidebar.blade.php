 <!-- sidebar @s -->
 <div class="nk-sidebar nk-sidebar-fixed is-dark " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-menu-trigger">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
        <div class="nk-sidebar-brand">
            <a href="" class="logo-link nk-sidebar-logo">
              <img class="logo-dark logo-img" src="{{ asset('/images/logo.png') }}" srcset="{{ asset('/images/logo.png') }}" alt="logo-dark">
              <img class="logo-light logo-img" src="{{ asset('/images/logo.png') }}" srcset="{{ asset('/images/logo.png') }}" alt="logo-dark">
            </a>
            </div>
        </div>
    <!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">

                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Dashboards</h6>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('role.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashlite"></em></span>
                            <span class="nk-menu-text">New Role</span>
                        </a>
                    </li>

                    @can('list_company')
                    <li class="nk-menu-item">
                        <a href="{{ route('company.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashlite"></em></span>
                            <span class="nk-menu-text">Company</span>
                        </a>
                    </li>
                    @endcan
                    <!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{ route('employee.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashlite"></em></span>
                            <span class="nk-menu-text">Employee</span>
                        </a>
                    </li><!-- .nk-menu-item -->



               <!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>
<!-- sidebar @e -->
