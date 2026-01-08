<!-- Jquery js -->
<script src="{{ asset('assets/frontend/js/jquery.min.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('assets/frontend/js/popper.min.js') }}"></script> 
<!-- Bootstrap js -->
<script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
<!-- Owl-Carousel js -->
<script src="{{ asset('assets/frontend/js/owl.carousel.min.js') }}"></script>
<!-- Lightgallery js -->
<!-- Slicknav js -->
<script src="{{ asset('assets/frontend/js/jquery.slicknav.min.js') }}"></script>
<!-- Magnific js -->

<!-- Nice Select js -->
<script src="{{ asset('assets/frontend/js/jquery.nice-select.min.js') }}"></script>
<!-- Datepicker JS -->



@yield('page-script')

@vite([ 
'resources/assets/frontend/js/main.js' 
])
