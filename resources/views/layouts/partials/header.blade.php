<!DOCTYPE html>
<html lang="en" dir="ltr"
    style="--primary01: rgba(98, 89, 202, 0.1); --primary02: rgba(98, 89, 202, 0.2); --primary03: rgba(98, 89, 202, 0.3); --primary06: rgba(98, 89, 202, 0.6); --primary09: rgba(98, 89, 202, 0.9);">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="HRMS for Tashi group">
    <meta name="author" content="Software Development Unit (Tashicell)">
    <meta name="robots" content="noindex, nofollow">

    <!-- FAVICON -->
    <link rel="shortcut icon"  href="{{ asset('assets/images/brand/logo3.png') }}">

    <!-- TITLE -->
    <title>@yield('name')</title>
    <!-- BOOTSTRAP CSS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- STYLE CSS -->
    <link href="{{ asset('assets/css/style.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/jquery-confirm.min.css') }}" rel="stylesheet">
    <!-- Animate css -->
    <link href="{{ asset('assets/css/animated.css') }}" rel="stylesheet">
    <!--- FONT ICONS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    @stack('page_styles')
</head>

<body class="app sidebar-mini ltr light-mode" data-new-gr-c-s-check-loaded="14.1098.0" data-gr-ext-installed="">
    <div class="horizontalMenucontainer">
        <!-- GLOBAL-LOADER -->
        <div id="global-loader">
            <img src="" />
            <img src="{{ asset('assets/images/loader.svg') }}" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOBAL-LOADER -->
