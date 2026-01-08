<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tRUSTY WHEELS | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('frontend.partials.css')
    <style>
        .card_ul{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        .card_ul li{
             background: #f1f1f1;
    background: #f1f1f1;
    padding: 2px 3px;
    border-radius: 10px;
    flex: 0 0 calc(33.33% - 10px);
    box-sizing: border-box;
    background: #f0f0f0;
    padding: 0px 0px;
    text-align: center;
        }
        /* Preloader Style */
        #preloader {
            position: fixed;
            width: 100%;
            height: 100vh;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #ec3323;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        #menuBtn{
           background: none;
  border: none;
  margin-top: 13px;
  padding: 0 !important;
        }
    </style>
    
</head>
<body style="overflow-inline: hidden;"> 
    <div id="preloader">
        <div class="loader"></div>
    </div>
 <div id="page">
    @include('frontend.components.topbar')
    @include('frontend.components.header')
   
    @include('frontend.components.menu')

    

    @yield('content')



    @include('frontend.components.footer')


    @include('frontend.partials.script')
</div>
    </body>
</html>