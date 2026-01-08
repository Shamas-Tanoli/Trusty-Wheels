@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('admin/layouts/blankLayout')

@section('title', 'Login ')

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
      <!-- Login -->
      <div class="card">
        <div class="card-body pt-4">
          <!-- Logo -->
          <div class="app-brand justify-content-center ">
            <a href="{{url('/')}}" class="app-brand-link">
              <span class="app-brand-logo demo">@include('admin/_partials.macros',['height'=>70,'width'=>'100%'])</span>
               </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}! </h4>
          <p class="mb-6 text-center ">Please sign-in to your account </p>

          <form id="formAuthentication" class="mb-4" action="{{ route('login.store') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="email" class="form-label">Email </label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email " autofocus>
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
            <div class="my-8">
              <div class="d-flex justify-content-between">
                <div class="form-check mb-0 ms-2">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                  <label class="form-check-label" for="remember-me">
                    Remember Me
                  </label>
                </div>
                {{-- <a href="{{url('auth/forgot-password-basic')}}">
                  <p class="mb-0">Forgot Password?</p>
                </a> --}}
              </div>
            </div>
            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>
          </form>

          
         

          
        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>
@endsection