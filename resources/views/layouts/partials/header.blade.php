<!DOCTYPE html>
<html lang="en" dir="ltr" style="--primary01: rgba(98, 89, 202, 0.1); --primary02: rgba(98, 89, 202, 0.2); --primary03: rgba(98, 89, 202, 0.3); --primary06: rgba(98, 89, 202, 0.6); --primary09: rgba(98, 89, 202, 0.9);">
<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="HRMS for Tashi group">
    <meta name="author" content="Software Development Unit (Tashicell)">
    <meta name="robots" content="noindex, nofollow">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/brand/favicon.ico') }}">
    <!-- TITLE -->
    <title>@yield('name')</title>
    <!-- BOOTSTRAP CSS -->
    
    <link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- STYLE CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">
    <!-- Animate css -->
    <link href="{{ asset('assets/css/animated.css') }}" rel="stylesheet">
    <!--- FONT ICONS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    @stack('page_styles')
</head>
<body class="app sidebar-mini ltr light-mode" data-new-gr-c-s-check-loaded="14.1098.0" data-gr-ext-installed="">
<div class="horizontalMenucontainer">
    <!-- GLOBAL-LOADER -->
    <div id="global-loader" style="display: none;">
        <img src="{{ asset('assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->