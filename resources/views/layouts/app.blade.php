
@include('layouts.partials.header')
@include('layouts.partials.sidebar')
    <!--app-content open-->
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <div>
                        @include('layouts.partials.breadcrumb')
                    </div>
                    <div class="ms-auto pageheader-btn">
                        @yield('buttons')
                    </div>
                </div>
                <!-- PAGE-HEADER END -->

                @include('layouts.includes.messages')


                <!-- Row -->
                <div class="row">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>
                <!--End Row-->
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
@include('layouts.partials.footer')
