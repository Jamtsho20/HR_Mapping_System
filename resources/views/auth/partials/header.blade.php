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

        <!-- TITLE -->
        <title>HRMS | @yield('page-title')</title>

        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/brand/favicon.ico') }}">

        <!-- BOOTSTRAP CSS -->
        <link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- STYLE CSS -->
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">

        <!--- FONT-ICONS CSS -->
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

        <meta http-equiv="imagetoolbar" content="no">
        <style type="text/css">
            .jqstooltip {
                position: absolute;
                left: 0px;
                top: 0px;
                visibility: hidden;
                background: rgb(0, 0, 0) transparent;
                background-color: rgba(0, 0, 0, 0.6);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
                -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
                color: white;
                font: 10px arial, san serif;
                text-align: left;
                white-space: nowrap;
                padding: 5px;
                border: 1px solid white;
                z-index: 10000;
            }
            .jqsfield {
                color: white;
                font: 10px arial, san serif;
                text-align: left;
            }
        </style>
        <style>
            .vt-augment {
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .vt-augment.drawer {
                display: none;
                width: 700px;
                background: white;
                border: 1px solid #e6e6e6;
                text-align: left;
                z-index: 102;
                position: fixed;
                right: 0;
                top: 0;
                height: 100vh;
                box-shadow: -4px 5px 8px -3px rgba(17, 17, 17, 0.16);
                animation: slideToRight 0.5s 1 forwards;
                transform: translateX(100vw);
            }
            .vt-augment.drawer[opened] {
                display: flex;
                animation: slideFromRight 0.2s 1 forwards;
            }
            .vt-augment > .spinner {
                position: absolute;
                z-index: 199;
                top: calc(50% - 50px);
                left: calc(50% - 50px);
                border: 8px solid rgba(0, 0, 0, 0.2);
                border-left-color: white;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 1.2s linear infinite;
            }
            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }
            @keyframes slideFromRight {
                0% {
                    transform: translateX(100vw);
                }
                100% {
                    transform: translateX(0);
                }
            }
            @keyframes slideToRight {
                100% {
                    transform: translateX(100vw);
                    display: none;
                }
            }
            @media screen and (max-width: 700px) {
                .vt-augment.drawer {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body class="login-img" data-new-gr-c-s-check-loaded="14.1098.0" data-gr-ext-installed="">
        <script type="text/javascript">
            function ukyb(c6gi){}
        </script>