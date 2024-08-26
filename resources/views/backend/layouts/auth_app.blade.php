<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="public/theam/assets/" data-template="vertical-menu-template-no-customizer">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Mega Solutions</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{url('public/theam/Images/logo.png')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/fonts/tabler-icons.css')}}" />
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/fonts/flag-icons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/css/rtl/core.css')}}" />
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/css/rtl/theme-default.css')}}" />
    <link rel="stylesheet" href="{{url('public/theam/assets/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="public/theam/assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{url('public/theam/assets/vendor/css/pages/page-auth.css')}}" />
    <!-- Helpers -->
    <script src="{{url('public/theam/assets/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{url('public/theam/assets/js/config.js')}}"></script>

    <style>
        body {
            background-image: url(../public/theam/Images/login_bg3.png);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat
        }
    </style>

</head>

<body>

    @yield('content')

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{url('public/theam/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{url('public/theam/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{url('public/theam/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{url('public/theam/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{url('public/theam/assets/vendor/libs/node-waves/node-waves.js')}}"></script>

    <script src="{{url('public/theam/assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{url('public/theam/assets/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{url('public/theam/assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>

    <script src="{{url('public/theam/assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{url('public/theam/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
    <script src="{{url('public/theam/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
    <script src="{{url('public/theam/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>

    <!-- Main JS -->
    <script src="{{url('public/theam/assets/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{url('public/theam/assets/js/pages-auth.js')}}"></script>
</body>

</html>
