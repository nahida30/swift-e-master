<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <title>@yield('title') - {{ config('app.name') }}</title>

  <meta charset="utf-8" />
  <meta content="" name="keywords" />
  <meta content="" name="description" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <link href="{{ asset('img/favicon.ico') }}" rel="icon" />
  <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon" />

  <link href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />

  <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/jquery-toast-plugin/css/jquery.toast.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/simple-datatables/style.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/aos/aos.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/loader.css') }}" rel="stylesheet" />
  <link href="{{ asset(request()->is('/') ? 'css/main.css' : 'css/style.css') }}" rel="stylesheet" />

  @stack('styles')
</head>

<body class="{{ request()->is('/') ? 'index-page' : '' }}">
  <div id="loader"><span class="loader"></span></div>

  @section('sidebar')@show

  @section('header')@show

  @yield('content')

  @section('footer')@show

  @if(request()->is('/'))
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  @else
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  @endif

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="{{ asset('vendor/jquery-toast-plugin/js/jquery.toast.min.js') }}"></script>
  <script src="{{ asset('vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('vendor/quill/quill.js') }}"></script>
  <script src="{{ asset('vendor/aos/aos.js') }}"></script>

  <script>
    function showLoader() {
      $('#loader').show()
    }

    function hideLoader() {
      $('#loader').hide()
    }

    window.addEventListener('popstate', function(event) {
      hideLoader()
    })

    $(document).ready(() => {
      $('[data-toggle="tooltip"]').tooltip()
      const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
      const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      })

      $('form').on('submit', function() {
        var form = this
        if (form.checkValidity()) {
          showLoader()
        }
      })

      setTimeout(hideLoader, 1000)
    })
  </script>

  @if(request()->is('/'))
  <script src="{{ asset('js/home.js') }}"></script>
  @else
  <script src="{{ asset('js/main.js') }}"></script>
  @endif
  @stack('scripts')

</body>

</html>