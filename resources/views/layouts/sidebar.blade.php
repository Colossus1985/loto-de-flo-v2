<?php
$user_id = isset($_SERVER['AUTHENTICATE_SAMACCOUNTNAME'])   ? strtolower($_SERVER['AUTHENTICATE_SAMACCOUNTNAME'])  : '';    // login (initiales)
$user_ok = $user_id == 'pr' || $user_id == 'eba' || $user_id == 'sgreiner' || $user_id == 'sg' ? true : false;
// Pour tests docker en local
$local = stripos($_SERVER['HTTP_HOST'], 'localhost')!==false ? true : false;
$user_ok = $local ? true : $user_ok;
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
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

                <li id="participants" class="nav-item{{ Request::segment(1) === 'participants' ? ' menu-is-opening menu-open' : null }}">

                    <a href="#" class="nav-link">
                        <p>
                            PARTICIPANTS
                            <i class="right fa text-yellow"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link {{Route::currentRouteNamed('cmdWeb') && Request::segment(3) === 'ALL' ? ' active' : ''}}">
                                <p>
                                    Ajouter
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{Route::currentRouteNamed('cmdWeb') && Request::segment(3) === 'ALL' ? ' active' : ''}}">
                                <p>
                                    Jouer
                                </p>
                            </a>
                        </li>
                    </ul>

                </li>

                {{-- ########################################################################################## --}}

                <li id="gains" class="nav-item{{ Request::segment(1) === 'participants' ? ' menu-is-opening menu-open' : null }}">
                    <a href="#" class="nav-link">
                        <p>
                            GAINS
                            <i class="right fa text-yellow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">

                        <li class="nav-item">
                            <a href="#" class="nav-link {{Route::currentRouteNamed('cmdWeb') && Request::segment(3) === 'ALL' ? ' active' : ''}}">
                                <p>
                                    Gains
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{Route::currentRouteNamed('cmdWeb') && Request::segment(3) === 'ALL' ? ' active' : ''}}">
                                <p>
                                    Ajouter Gain
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ########################################################################################## --}}

                <li id="groupes" class="nav-item{{ Request::segment(1) === 'participants' ? ' menu-is-opening menu-open' : null }}">
                    <a href="#" class="nav-link">
                        <p>
                            GROUPES
                            <i class="right fa text-yellow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">

                        <li class="nav-item">
                            <a href="#" class="nav-link {{Route::currentRouteNamed('cmdWeb') && Request::segment(3) === 'ALL' ? ' active' : ''}}">
                                <p>
                                    Créer Groupe
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{Route::currentRouteNamed('cmdWeb') && Request::segment(3) === 'ALL' ? ' active' : ''}}">
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