@php
    $segments = request()->segments();
@endphp
<div class="left-side-bar">
    <div class="brand-logo">
        <a href="#">
            <img src="{{asset('vendors/images/deskapp-logo.svg')}}" alt="" class="dark-logo" />
            <img src="{{asset('vendors/images/deskapp-logo-white.svg')}}" alt="" class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="{{route('dashboard')}}" class="dropdown-toggle no-arrow {{in_array('dashboard',$segments)?'active':''}}"> <span class="micon fa fa-home"></span><span class="mtext">Dashboard</span> </a>
                </li>
            </ul>
        </div>
    </div>
</div>