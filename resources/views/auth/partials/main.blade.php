@include('auth.partials.header')
    <!-- BACKGROUND-IMAGE -->
    <div>
        <!-- GLOABAL LOADER -->
        <div id="global-loader" style="display: none;">
            <img src="{{ asset('assets/images/loader.svg') }}" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOABAL LOADER -->
        @yield('content')
    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->
@include('auth.partials.footer')