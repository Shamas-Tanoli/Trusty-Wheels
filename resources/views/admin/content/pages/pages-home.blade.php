@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\DB;
@endphp

@extends('admin/layouts/layoutMaster')

@section('title', 'Home')

@section('content')

        @include('admin.content.pages.adminHome')

@endsection