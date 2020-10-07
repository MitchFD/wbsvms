@include('layouts.navbars.navs.guest')

{{-- style="background-image: url(../storage/svms/sys/img/sdca_bg1.jpg) !important;" --}}
<div class="wrapper wrapper-full-page ">
    <div class="full-page section-image login_bg" filter-color="black" style="background-image: url(../../storage/{{$backgroundImagePath}}) !important;">
        @yield('content')
        @include('layouts.footer')
    </div>
    {{-- <div class="full-page section-image" filter-color="black" data-image="{{ asset('paper') . '/' . ($backgroundImagePath ?? "img/bg/fabio-mangione.jpg") }}">
        @yield('content')
        @include('layouts.footer')
    </div> --}}
</div>
