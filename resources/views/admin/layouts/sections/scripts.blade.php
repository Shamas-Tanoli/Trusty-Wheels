<!-- BEGIN: Vendor JS-->

@vite([
'resources/assets/admin/vendor/libs/jquery/jquery.js',
'resources/assets/admin/vendor/libs/popper/popper.js',
'resources/assets/admin/vendor/js/bootstrap.js',
'resources/assets/admin/vendor/libs/node-waves/node-waves.js',
'resources/assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
'resources/assets/admin/vendor/libs/hammer/hammer.js',
'resources/assets/admin/vendor/libs/typeahead-js/typeahead.js',
'resources/assets/admin/vendor/js/menu.js',
'resources/assets/admin/vendor/libs/toastr/toastr.js',
'resources/assets/admin/vendor/libs/sweetalert2/sweetalert2.js',
'resources/assets/admin/vendor/libs/block-ui/block-ui.js' 
])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/admin/js/main.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@vite(['resources/assets/admin/js/ui-toasts.js','resources/assets/admin/js/extended-ui-sweetalert2.js'])
@yield('page-script')
<!-- END: Page JS-->
