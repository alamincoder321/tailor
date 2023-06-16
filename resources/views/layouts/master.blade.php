<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title')</title>
    <link rel="icon" href="{{asset('noImage.png')}}" type="image/gif" sizes="80x80">
    <link href="{{asset('backend')}}/css/styles.css" rel="stylesheet" />
    <link href="{{asset('backend')}}/css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('font-awesome/css/all.css')}}" />

    @vite('resources/css/app.css')
    <style>
        .ImageBackground .imageShow {
            display: block;
            height: 120px;
            width: 125px;
            margin-top: 5px;
            border: 1px solid #747474;
            box-sizing: border-box;
        }

        .ImageBackground input {
            display: none;
        }

        .ImageBackground label {
            background: #817575;
            width: 125px;
            color: white;
            text-align: center;
            cursor: pointer;
            font-family: monospace;
            text-transform: uppercase;
            margin-top: 10px;
            border: 3px solid darkgrey;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    @include("layouts.backend.navbar")
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include("layouts.backend.sidebar")

        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h6 class="mt-4">@yield('bread_crum')</h6>
                    @yield("content")
                </div>
            </main>
        </div>
    </div>
    @vite('resources/js/app.js')
    <script src="{{asset('backend')}}/js/scripts.js"></script>
</body>

</html>