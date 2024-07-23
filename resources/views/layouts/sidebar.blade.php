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
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

{{-- ########################################################################################################### --}}
            <li id="main_cmd_web" class="nav-item{{ Request::segment(1) === 'cmde' ? ' menu-is-opening menu-open' : null }}">
                {{-------------------------------------------------------------------------- 
                    COMMANDES WEB
                --------------------------------------------------------------------------}}
                <a href="#" class="nav-link">
                    {{-- <i class="nav-icon fa fa-circle text-yellow"></i> --}}
                    <p>
                        COMMANDES WEB
                        <i class="right fa text-yellow"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview pl-3">
                    </ul>
                <ul class="nav nav-treeview pl-3">
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa fa-area-chart text-yellow text-S"></i> 
                            <p class="ms-2">
                                Stats
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            <li class="nav-item ">
                                <a href="" class="nav-link">
                                    <i class="fa-solid fa-signal text-S"></i>
                                    <p class="ms-2">
                                        Evolution
                                        <i class="right fas"></i>
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            
                <li  id="main_cmd_web" class="">
                    <a href="#" class="nav-link">
                        <p>PIM</p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <p>
                                    Liste des produits
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>  


{{-- ############################################################################################################## --}}
            </ul>
        </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>