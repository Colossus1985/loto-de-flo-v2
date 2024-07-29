<style type="text/css">

/* Animation */
@keyframes scroll_fonds {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-250px * {{ $tailleSommesFonds }}));
    }
}

/* Animation */
@keyframes scroll_gains {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-250px * {{ $tailleSommesGains }}));
    }
}

/* Styling */
.slider {
    background: #FFC006;
    height: 30px;
    margin: auto;
    overflow: hidden;
    position: relative;
    width: 50%;
}

.slider::before,
.slider::after {
    background: linear-gradient(to right, #FFC006 0%, rgba(255, 255, 255, 0) 100%);
    content: "";
    height: 30px;
    position: absolute;
    width: 200px;
    z-index: 2;
}

.slider::after {
    right: 0;
    top: 0;
    transform: rotateZ(180deg);
}

.slider::before {
    left: 0;
    top: 0;
}

.slide-track-gains {
    animation: scroll_gains 10s linear infinite;
    display: flex;
    width: calc(250px * 2 * {{ $tailleSommesGains }});
}

.slide-track-fonds {
    animation: scroll_fonds 10s linear infinite;
    display: flex;
    width: calc(250px * 2 * {{ $tailleSommesFonds }});
}

.slide {
    height: 30px;
    width: 250px;
}

</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light bg-warning">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link"></a>
        </li>
    </ul>

    <div class="slider">
        <div class="slide-track-fonds">
            @foreach ($sommeFondsByGroups as $sommeFondsByGroup)
                <div class="slide">
                    <div class="mx-3 d-flex justify-content-between align-items-center text-nowrap">
                        <h5 class="me-3">{{ $sommeFondsByGroup['nameGroup'] }} : </h5>
                            <h5 class="text-info"> {{ $sommeFondsByGroup['fonds'] }} €</h5>
                    </div>
                </div>
            @endforeach
            @foreach ($sommeFondsByGroups as $sommeFondsByGroup)
                <div class="slide">
                    <div class="mx-3 d-flex justify-content-between align-items-center text-nowrap ">
                        <h5 class="me-3">{{ $sommeFondsByGroup['nameGroup'] }} : </h5>
                        <h5 class="text-info"> {{ $sommeFondsByGroup['fonds'] }} €</h5>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="slider">
        <div class="slide-track-gains">
            @foreach ($sommeGainsByGroups as $sommeGainsByGroup)
                <div class="slide">
                    <div class="mx-3 d-flex justify-content-between align-items-center text-nowrap">
                        <h5 class="me-3">{{ $sommeGainsByGroup['nameGroup'] }} :</h5>
                        <h5 class="text-info"> {{ $sommeGainsByGroup['sommeGains'] }} €,</h5>
                    </div>
                </div>
            @endforeach
            @foreach ($sommeGainsByGroups as $sommeGainsByGroup)
                <div class="slide">
                    <div class="mx-3 d-flex justify-content-between align-items-center text-nowrap">
                        <h5 class="me-3">{{ $sommeGainsByGroup['nameGroup'] }} :</h5>
                        <h5 class="text-info"> {{ $sommeGainsByGroup['sommeGains'] }} €,</h5>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
