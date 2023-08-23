<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from gull-html-laravel.ui-lib.com/others/starter by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 05 Sep 2022 12:43:54 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

@include('template.partials.head')

<body class="text-left">
    <!-- Pre Loader Strat  -->
    <div class="loadscreen" id="preloader">
        <div class="loader spinner-bubble spinner-bubble-primary"></div>
    </div>
    <!-- Pre Loader end  -->

    <!-- ============ Compact Layout start ============= -->
    <!-- ============Deafult  Large SIdebar Layout start ============= -->

    <div class="app-admin-wrap layout-sidebar-large clearfix">
        @include('template.partials.header')
        <!-- header top menu end -->

        @include('template.partials.sidebar')
        <!--=============== Left side End ================-->

        <!-- ============ Body content start ============= -->
        <div class="main-content-wrap sidenav-open d-flex flex-column">
            <div class="main-content">
                <div class="breadcrumb">
                    <h1>@yield('page-title')</h1>
                    <ul>
                        <li><a href="#">@yield('page-sub-title')</a></li>
                        <li>@yield('page-title')</li>
                    </ul>
                </div>

                <div class="separator-breadcrumb border-top"></div>
                @yield('modal')
                @yield('content')
            </div>
            <!-- Footer Start -->
            @include('template.partials.footer')
            <!-- fotter end -->
        </div>
        <!-- ============ Body content End ============= -->
    </div>
    <!--=============== End app-admin-wrap ================-->

    <!-- ============ Large Sidebar Layout End ============= -->

    @include('template.partials.script')
</body>

<!-- Mirrored from gull-html-laravel.ui-lib.com/others/starter by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 05 Sep 2022 12:44:17 GMT -->

</html>
