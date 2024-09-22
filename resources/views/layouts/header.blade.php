<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
        <div class="header-search">
            <form>
                <div class="form-group mb-0">
                    <i class="dw dw-search2 search-icon"></i>
                    <input type="text" class="form-control search-input" placeholder="Search Here" />
                    
                </div>
            </form>
        </div>
    </div>
    <div class="header-right">
        
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="{{asset('vendors/images/photo1.jpg')}}" alt="" />
                    </span>
                    <span class="user-name">2FA Laravel</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="#"><i class="dw dw-user1"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="dw dw-settings2"></i> Setting</a>
                    <a class="dropdown-item" href="#"><i class="dw dw-help"></i> Help</a>
                    <a class="dropdown-item" href="{{route('logout')}}"><i class="dw dw-logout"></i> Log Out</a>
                </div>
            </div>
        </div>
        
    </div>
</div>