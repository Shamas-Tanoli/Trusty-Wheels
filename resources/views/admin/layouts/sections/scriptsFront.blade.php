
<!-- BEGIN: Vendor JS-->
@vite([
  'resources/assets/admin/vendor/js/dropdown-hover.js',
  'resources/assets/admin/vendor/js/mega-dropdown.js',
  'resources/assets/admin/vendor/libs/popper/popper.js',
  'resources/assets/admin/vendor/js/bootstrap.js',
  'resources/assets/admin/vendor/libs/node-waves/node-waves.js'
])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/admin/js/front-main.js'])
<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
