<!-- Mmenu CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jQuery.mmenu/9.3.0/mmenu.min.css"
    crossorigin="anonymous" />


<!-- Page Wrapper -->


<section class="gauto-mainmenu-area">
    <div class="container">
        <div class="row " style="justify-content: space-between;">

            <div class=" d-md-none col-8">
            <div class="site-logos">
               <a href="{{ url('/') }}">
                  <img style="max-width:46%;" src={{ asset("assets/frontend/img/layer_2.png") }} alt="sito logo">
               </a>
            </div>
         </div>

            <!-- Mobile Trigger -->
           <div class="col-2 d-lg-none">
    <button id="menuBtn" class="btn px-3 py-2" style="color:#000;font-size:22px;margin-top:0;">
        ☰
    </button>
</div>

            <!-- Desktop Menu -->
            <div class="col-lg-12 d-none d-lg-block">
                <div class="mainmenu ">
                    <nav>
                        <ul>
                            <li class="{{ request()->routeIs('home*') ? 'active' : '' }}">
                                <a href="{{ route('home') }}">Home</a>
                            </li>


                            <li class="nav-item custom-dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                     Vehicle
                                </a>
                                <ul class=" custom-dropdown-menu">
                                    <li class="{{ request()->routeIs('vehicle*') ? 'active' : '' }}">
                                        <a class="dropdown-item" href="">Sold Vehicles</a>
                                    </li>
                                    <li class="{{ request()->routeIs('vehicle*') ? 'active' : '' }}">
                                        <a class="dropdown-item" href="{{ route('available.vehicle') }}">Available Vehicles</a>
                                    </li>
                                    <li class="{{ request()->routeIs('vehicle*') ? 'active' : '' }}">
                                        <a class="dropdown-item" href="{{ route('arriving.vehicle') }}">Arriving Soon</a>
                                    </li>
                                </ul>
                            </li>

                            <style>
                                .custom-dropdown-menu {
                                    border-radius: 8px;
                                    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
                                    padding: 0.4rem 0;
                                    border: 1px solid #e6e6e6;color: #0000;
                                }

                                .custom-dropdown-menu .dropdown-item {
                                    padding: 0.55rem 0.9rem;
                                    font-size: 0.95rem;
                                }

                                .custom-dropdown-menu .dropdown-item:hover {
                                    background-color: #f2f6ff;
                                    
                                }
                               .custom-dropdown ul li a {
                                color: #000000 !important;
                               }
                            </style>




                            <li class="{{ request()->routeIs('warranty*') ? 'active' : '' }}">
                                <a href="{{ route('warranty') }}">Car Warranty</a>
                            </li>
                            <li class="{{ request()->routeIs('partexchange*') ? 'active' : '' }}">
                                <a href="{{ route('partexchange') }}">Part Exchange</a>
                            </li>
                            <li class="{{ request()->routeIs('about*') ? 'active' : '' }}">
                                <a href="{{ route('about') }}">About</a>
                            </li>
                            <li class="{{ request()->routeIs('contact*') ? 'active' : '' }}">
                                <a href="{{ route('contact') }}">Contact</a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- /#page -->
<style>
.mobile-offcanvas {
    position: fixed;
    top: 0;
    left: -260px; /* hide by default */
    width: 260px;
    height: 100%;
    background: #fff;
    box-shadow: 2px 0 10px rgba(0,0,0,0.3);
    z-index: 1050;
    transition: left 0.3s ease;
    overflow-y: auto;
}

.mobile-offcanvas.show {
    left: 0;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const menuBtn = document.getElementById("menuBtn");
    const closeBtn = document.getElementById("closeBtn");
    const offcanvas = document.getElementById("mobileOffcanvas");

    menuBtn.addEventListener("click", () => {
        offcanvas.classList.add("show");
    });

    closeBtn.addEventListener("click", () => {
        offcanvas.classList.remove("show");
    });
});
</script>

<!-- Mobile Menu (hidden initially) -->
<div id="mobileOffcanvas" class="mobile-offcanvas">
    <div class="offcanvas-header d-flex justify-content-between align-items-center p-3">
        <h5 class="mb-0">Menu</h5>
        <button class="btn btn-sm btn-light" id="closeBtn">&times;</button>
    </div>
    <div class="offcanvas-body p-3">
        <ul class="navbar-nav">
            <li class="{{ request()->routeIs('home*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
            </li>

            <!-- Dropdown -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#vehicleMenu" role="button" aria-expanded="false" aria-controls="vehicleMenu">
                    Vehicle <i class="fa fa-angle-down"></i> 
                </a>
                <div class="collapse" id="vehicleMenu">
                    <ul class="list-unstyled ml-3">
                        <li><a class="nav-link" href="">Sold Vehicles</a></li>
                        <li><a class="nav-link" href="#">Available Vehicles</a></li>
                        <li><a class="nav-link" href="#">Arriving Soon</a></li>
                    </ul>
                </div>
            </li>

            <li class="{{ request()->routeIs('warranty*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('warranty') }}">Car Warranty</a>
            </li>
            <li class="{{ request()->routeIs('partexchange*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('partexchange') }}">Part Exchange</a>
            </li>
            <li class="{{ request()->routeIs('about*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('about') }}">About</a>
            </li>
            <li class="{{ request()->routeIs('contact*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('contact') }}">Contact</a>
            </li>
        </ul>
    </div>
</div>



