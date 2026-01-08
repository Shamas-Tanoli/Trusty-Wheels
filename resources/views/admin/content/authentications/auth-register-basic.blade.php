@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('admin/layouts/blankLayout')

@section('title', 'Register ')

@section('vendor-style')
@vite([
'resources/assets/admin/vendor/libs/@form-validation/form-validation.scss' 
])
@endsection

@section('page-style')
@vite([
'resources/assets/admin/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/admin/vendor/libs/@form-validation/popular.js',
'resources/assets/admin/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
@vite([
'resources/assets/admin/js/pages-auth.js'
])
@endsection

@section('content')


<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">

     
      <!-- Register Card -->
      <div class="card"> 
        <div class="card-body pt-4">
          <!-- Logo -->
          <div class="app-brand justify-content-center ">
            <a href="{{url('/')}}" class="app-brand-link">
              <span class="app-brand-logo demo">
                @include('admin/_partials.macros',['height'=>100])

                
              </span>

              <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Register Yourself 🚗 </h4>
          <p class="mb-4 text-center text-gray-600"> Join us for a better driving experience! </p>


          <form id="formAuthentication" class="mb-6" action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" autofocus>
            </div>
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email">
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>

            <div class="mb-4 form-password-toggle">
              <label class="form-label" for="password_confirmation">Confirm-Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>




            <div>
              <div class="form-check ps-0 mb-0 ">

                <i class="ti ti-arrow-big-right ti-fade-right"></i>
                <a href="{{ asset('assets/agreement/agreement.pdf') }}" download class="ms-2">
                  Download Agreement
                </a>

              </div>
            </div>


            <div class="my-4">
              <div class="form-check mb-0 ms-2">
                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
                <label class="form-check-label" for="terms-conditions">
                  I agree to
                  <a href="javascript:void(0);">privacy policy & terms</a>
                </label>
              </div>
            </div>


            <button class="btn btn-primary d-grid w-100">
              Sign up
            </button>
          </form>

          <p class="text-center">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}">
              <span>Sign in instead</span>
            </a>
          </p>

          

          
        </div>
      </div>
      <!-- Register Card -->
    </div>
  </div>
</div>
@endsection