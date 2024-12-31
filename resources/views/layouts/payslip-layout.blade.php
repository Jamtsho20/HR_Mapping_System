<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table.bordered {
            border-collapse: collapse;
            border: 1px solid black;
        }

        table.bordered,
        table.bordered tr td,
        table.bordered tr th {
            page-break-inside: avoid;
        }

        @media print {

            table,
            table tr td,
            table tr th {
                page-break-inside: avoid;
            }
        }

        table.bordered th,
        table.bordered td {
            border: 1px solid black;
        }

        table.borderless {
            border: none;
        }

        div.borderonlyleft {
            border-left: none;
            border-top: none;
            border-bottom: none;
            border-left: 1px solid #000;
        }

        div.borderonlyright {
            border-left: none;
            border-top: none;
            border-bottom: none;
            border-right: 1px solid #000;
        }

        table.col-height-mid td,
        table.col-height-mid th {
            height: 25px;
        }

        body {
            padding-top: 20px !important;
            padding-left: 30px !important;
            padding-right: 30px !important;
            line-height: 23px !important;
            font-size: 15.5px;
        }

        header {
            position: fixed;
            left: 0px;
            right: 0px;
            height: 100px;
        }

        footer {
            position: fixed;
            margin-left: 4.5%;
            width: 85%;
            bottom: 24px;
            padding-top: 15px;
            line-height: 18px !important;
        }

        .text-ucase {
            text-transform: uppercase;
        }

        .text-bold {
            font-weight: bold;
        }

        .lightborder-topbottom {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        .blue-text {
            color: #4311FB;
        }

        .w-half {
            width: 50%
        }

        .w-full {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .pr-10 {
            padding-right: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .font-0_9 {
            font-size: 0.9rem;
        }

        .font-1_0 {
            font-size: 1.0rem;
        }

        .font-1_3 {
            font-size: 1.3rem;
        }

        .h-500 {
            height: 500px;
        }

        .row-wrapper {
            display: inline-block;
            vertical-align: top;
        }

        .w-32 {
            width: 32%;
        }

        .clearfix {
            clear: both;
        }

        .valign-top {
            vertical-align: top
        }

        .font-small {
            font-size: 0.9rem;
        }

        .row-wrapper div {
            display: inline-block;
        }

        .mb-6 {
            margin-bottom: 6px;
        }

        .mt-5 {
            margin-top: 5px;
        }

        .pt-10 {
            padding-top: 10px;
        }

        @page {
            margin: 25px 25px 50px 25px;
            padding-bottom: 20px;
            padding-top: 30px;
        }

        [data-f-id="pbf"] {
            display: none !important;
        }
    </style>
</head>

<body>
    @include('layouts.includes.letter-head')
    <main>
        <div>
            <div class="font-1_3 mt-5 mb-6" style="border-top: 1px solid #000; padding-top: 7px;">
                <center><strong>PAY SLIP</strong></center>
            </div>
            @yield('content')
        </div>
    </main>
    <footer>
        <br>
        <table
            style="bottom:15px;width:100%;border-top:1px solid #000;border-left:0; border-right:none; border-bottom:none;">
            <tr>
                <td colspan="2" style="border:none;text-align:center;color:#C04424;font-weight:bold;">Address: P.O
                    Box
                    # 1502, Norzin Lam, Thimphu: Bhutan</td>
            </tr>
            <tr>
                <td colspan="2" style="border:none;text-align:center;">Phone: +975 77889977 Website: <a
                        href="https://www.tashicell.com">www.tashicell.com</a></td>
            </tr>
        </table>
    </footer>
</body>

</html>
