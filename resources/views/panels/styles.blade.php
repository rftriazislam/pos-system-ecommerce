<!-- BEGIN: Vendor CSS-->


@if($configData['direction'] === 'rtl' && isset($configData['direction']))
	<link rel="stylesheet" href="{{ asset('public/'.mix('vendors/css/vendors-rtl.min.css')) }}" />
@else
	<link rel="stylesheet" href="{{ asset('public'.mix('vendors/css/vendors.min.css')) }}" />
@endif
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- vendor css files select 2 -->
<link rel="stylesheet" href="{{ asset('resources/'.mix('vendors/css/forms/select/select2.min.css')) }}" />
<link rel="stylesheet" href="{{ URL::asset('public/vendors/css/tagify/tagify.css') }}" />
@yield('vendor-style')
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" href="{{ asset('public/'.mix('css/core.css')) }}" />
@php $configData = Helper::applClasses(); @endphp

<!-- BEGIN: Page CSS-->
@if($configData['mainLayoutType'] === 'horizontal')
	<link rel="stylesheet" href="{{ asset('public'.mix('css/base/core/menu/menu-types/horizontal-menu.css')) }}" />
@else
	<link rel="stylesheet" href="{{ asset('public'.mix('css/base/core/menu/menu-types/vertical-menu.css')) }}" />
@endif

{{-- Page Styles --}}
@yield('page-style')

<!-- laravel style -->
<link rel="stylesheet" href="{{ asset('public'.mix('css/overrides.css')) }}" />

<!-- BEGIN: Custom CSS-->

@if($configData['direction'] === 'rtl' && isset($configData['direction']))
	<link rel="stylesheet" href="{{ asset('public/'.mix('css-rtl/custom-rtl.css')) }}" />
	<link rel="stylesheet" href="{{ asset('public/'.mix('css-rtl/style-rtl.css')) }}" />
@else
	{{-- user custom styles --}}
	<link rel="stylesheet" href="{{ asset('public/'.mix('css/style.css')) }}" />
@endif
<link rel="stylesheet" href="{{ asset('public/css/custom/modal.css') }}" />
