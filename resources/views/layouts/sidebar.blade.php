<?php
$user_id = isset($_SERVER['AUTHENTICATE_SAMACCOUNTNAME'])   ? strtolower($_SERVER['AUTHENTICATE_SAMACCOUNTNAME'])  : '';    // login (initiales)
$user_ok = $user_id == 'pr' || $user_id == 'eba' || $user_id == 'sgreiner' || $user_id == 'sg' ? true : false;
// Pour tests docker en local
$local = stripos($_SERVER['HTTP_HOST'], 'localhost')!==false ? true : false;
$user_ok = $local ? true : $user_ok;
?>

<style>
    .main-sidebar__{
        background-color: #0083ff;
        color: #ffffff;
    }
</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="ps-3 brand-link">
      <img src="{{asset('img/logo-dl-rs.png')}}" alt="Digilife" class="brand-image img-circle elevation-3">
      <span class="brand-text ms-4">WEB</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li id="dashbord" class="nav-item{{ Request::segment(1) === 'dashbord' ? ' menu-is-opening menu-open' : null }}">

                    <li class="nav-item">
                        <a href="{{ route('dashbord') }}" class="nav-link {{Route::currentRouteNamed('dashbord') && Request::segment(1) === 'dashbord' ? ' active' : ''}}">
                            <p>
                                DASHBORD
                            </p>
                        </a>
                    </li>

                </li>

                <li id="participants" class="nav-item{{ Request::segment(1) === 'participants' ? ' menu-is-opening menu-open' : null }}">

                    <a href="#" class="nav-link">
                        <p>
                            PARTICIPANTS
                            <i class="right fa text-yellow"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ route('addParticipantForm') }}" class="nav-link {{Route::currentRouteNamed('addParticipantForm') && Request::segment(1) === 'participants' ? ' active' : ''}}">
                                <p>
                                    Ajouter
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jouerForm') }}" class="nav-link  {{Route::currentRouteNamed('jouerForm') && Request::segment(1) === 'participants' ? ' active' : ''}}">
                                <p>
                                    Jouer
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('participants') }}" class="nav-link {{Route::currentRouteNamed('participants') && Request::segment(1) === 'participants' ? ' active' : ''}}">
                                <p>
                                    Joueurs
                                </p>
                            </a>
                        </li>
                    </ul>

                </li>

                {{-- ########################################################################################## --}}

                <li id="gains" class="nav-item{{ Request::segment(1) === 'gains' ? ' menu-is-opening menu-open' : null }}">
                    <a href="#" class="nav-link">
                        <p>
                            GAINS
                            <i class="right fa text-yellow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">

                        <li class="nav-item">
                            <a href="{{ route('getGainHistory') }}" class="nav-link {{Route::currentRouteNamed('getGainHistory') && Request::segment(1) === 'gains' ? ' active' : ''}}">
                                <p>
                                    Gains
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('addGainForm') }}" class="nav-link {{Route::currentRouteNamed('addGainForm') && Request::segment(1) === 'gains' ? ' active' : ''}}">
                                <p>
                                    Ajouter Gain
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ########################################################################################## --}}

                <li id="groups" class="nav-item{{ Request::segment(1) === 'groups' ? ' menu-is-opening menu-open' : null }}">
                    <a href="#" class="nav-link">
                        <p>
                            GROUPES
                            <i class="right fa text-yellow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">

                        <li class="nav-item">
                            <button class="nav-link text-left" data-bs-toggle="modal"
                                data-bs-target="#modalAddGroup">
                                Créer Groupe
                            </button>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('participantGroupForm') }}" class="nav-link {{Route::currentRouteNamed('participantGroupForm') && Request::segment(1) === 'groups' ? ' active' : ''}}">
                                <p>
                                    Gérer Groupe
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
@include('modals.addGroup')