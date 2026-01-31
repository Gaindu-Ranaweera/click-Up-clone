<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

<!-- plugins:css -->
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/feather/feather.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/ti-icons/css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/typicons/typicons.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/css/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<!-- endinject -->

<!-- Plugin css for this page -->
@stack('plugin-styles')
<!-- End plugin css for this page -->

<!-- inject:css -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/device.css') }}">
<!-- endinject -->

<link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" />

<!-- Custom styles -->
@stack('styles')
