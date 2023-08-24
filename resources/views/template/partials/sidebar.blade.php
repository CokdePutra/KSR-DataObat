<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <div class="navigation-left">
            <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('dashboard.index') }}">
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="nav-text">Dashoard</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ Str::contains(request()->url(), 'category') ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('category.index') }}">
                    <i class="nav-icon i-Administrator"></i>
                    <span class="nav-text">Category</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ Str::contains(request()->url(), 'medicine') ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('medicine.index') }}">
                    <i class="nav-icon i-Administrator"></i>
                    <span class="nav-text">Medicine</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ Str::contains(request()->url(), 'batch') ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('batch.index') }}">
                    <i class="nav-icon i-Administrator"></i>
                    <span class="nav-text">Batch</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ Str::contains(request()->url(), 'outgoing') ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{route('outgoing.index')}}">
                    <i class="nav-icon i-Administrator"></i>
                    <span class="nav-text">Outgoing</span>
                </a>
                <div class="triangle"></div>
            </li>
        </div>
    </div>

    {{-- <div class="sidebar-left-secondary rtl-ps-none ps" data-perfect-scrollbar="" data-suppress-scroll-x="true">
        <!-- Submenu Dashboards -->
        <ul class="childNav" data-parent="manage" style="display: block;">
            <li class="nav-item">
                <a class="{{ Request::is('manage/incoming-medicine') ? 'open' : ''}}" href="{{route('manage.incoming.medicine.index')}}">
                    <i class="nav-icon i-Clock-3"></i>
                    <span class="item-name">Incoming Medicine</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="{{ Request::is('manage/outgoing-medicine') ? 'open' : ''}}" href="{{route('manage.outgoing.medicine.index')}}">
                    <i class="nav-icon i-Clock-4"></i>
                    <span class="item-name">Outgoing Medicine</span>
                </a>
            </li>
        </ul>

        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </div> --}}

    <div class="sidebar-overlay"></div>
</div>
