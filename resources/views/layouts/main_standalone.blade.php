<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lot de Flo</title>

    @include('dependencys.htmldependencies')
    
</head>
<body class="standalone">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        {{-- <div class="content-wrapper"> --}}
        <div>
          <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
    </div>
</body>
@include('dependencys.scripts')
</html>
