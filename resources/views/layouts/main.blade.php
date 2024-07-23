<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="img/faviconCtrlDl.ico" />
    <title>Lot de Flo</title>

    @include('dependencys.htmldependencies')
    
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <section>
            @include('layouts.loader')
        </section>


        @include('layouts.navbar')
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

          <!-- Main content -->
            <section class="content">
                <div class="container-fluid" id="app">
                    <div class="mx-5 my-2">
                        @if (session('success'))
                            <p class="alert alert-success mt-3">{{ session('success') }}</p>
                        @endif
                        @if (session('error'))
                            <p class="alert alert-danger mt-3">{{ session('error') }}</p>
                        @endif
                    </div>
                    @yield('content')
                </div>
            </section>
           
        </div>
        
        @include('layouts.footer')
        @include('layouts.sideControl')
    </div>
</body>
@include('dependencys.scripts')
</html>
