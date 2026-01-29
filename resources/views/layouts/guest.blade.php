<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('backend-includes.header-sources')
    
    <style>
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        .preloader.fade-out {
            opacity: 0;
            pointer-events: none;
        }
        .loader {
            width: 50px;
            aspect-ratio: 1;
            display: grid;
            color: #4B49AC;
            background: radial-gradient(farthest-side, currentColor 98%,#0000) center/12px 12px,
                radial-gradient(farthest-side, currentColor 98%,#0000) center/12px 12px;
            background-repeat: no-repeat;
            animation: l3 1s infinite;
        }
        .loader::before,
        .loader::after {
            content: "";
            grid-area: 1/1;
            background: inherit;
            opacity: 0.5;
            animation: inherit;
            animation-delay: -0.25s;
        }
        .loader::after {
            opacity: 0.25;
            animation-delay: -0.5s;
        }
        @keyframes l3 {
            0%    {background-position: 0% 50%, 100% 50%}
            20%   {background-position: 0% 20%, 100% 20%}
            40%   {background-position: 0% 80%, 100% 80%}
            60%   {background-position: 0% 50%, 100% 50%}
            80%   {background-position: 20% 50%, 80%  50%}
            100%  {background-position: 50% 50%, 50%  50%}
        }

        body {
            background: linear-gradient(-45deg, #4B49AC, #98BDFF, #7DA0FA, #EEF2FF);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .auth-form-light {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px !important;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2) !important;
        }
    </style>
</head>
<body>
    <div class="preloader" id="preloader">
        <span class="loader"></span>
    </div>

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend-includes.footer-sources')

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('preloader').classList.add('fade-out');
            }, 800);
        });
    </script>
</body>
</html>
