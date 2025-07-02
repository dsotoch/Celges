<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion Interna Jamb.</title>
    <link rel="shortcut icon"
        href="https://jamb.pe/wp-content/uploads/2020/08/JAMB-TEHNOLOGY-CALIDAD-Y-GARANTIA-A-TU-SERVICIO-01.svg" />
    <link rel="stylesheet" href="{{ asset('melody/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('melody/style.css') }}" />
    @yield('estilos')
    <!-- Archivos que estás desarrollando con Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row default-layout-navbar">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="index-2.html"><img
                        src="https://jamb.pe/wp-content/uploads/2020/08/JAMB-TEHNOLOGY-CALIDAD-Y-GARANTIA-A-TU-SERVICIO-01.svg"
                        alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index-2.html"><img
                        src="https://jamb.pe/wp-content/uploads/2020/08/JAMB-TEHNOLOGY-CALIDAD-Y-GARANTIA-A-TU-SERVICIO-01.svg"
                        alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" id="btn-minimize" type="button"
                    data-toggle="minimize">
                    <span class="fas fa-bars"></span>
                </button>
                <ul class="navbar-nav">
                    <li class="nav-item nav-search d-none d-md-flex">
                        <div class="nav-link">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Search" aria-label="Search">
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item d-none d-lg-flex">
                        <a class="nav-link" href="#">
                            <span class="btn btn-primary">+ Create new</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown d-none d-lg-flex">
                        <div class="nav-link">
                            <span class="dropdown-toggle btn btn-outline-dark" id="languageDropdown"
                                data-toggle="dropdown">English</span>
                            <div class="dropdown-menu navbar-dropdown" aria-labelledby="languageDropdown">
                                <a class="dropdown-item font-weight-medium" href="#">
                                    French
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item font-weight-medium" href="#">
                                    Espanol
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item font-weight-medium" href="#">
                                    Latin
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item font-weight-medium" href="#">
                                    Arabic
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                            data-toggle="dropdown">
                            <i class="fas fa-bell mx-0"></i>
                            <span class="count">16</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown">
                            <a class="dropdown-item">
                                <p class="mb-0 font-weight-normal float-left">You have 16 new notifications
                                </p>
                                <span class="badge badge-pill badge-warning float-right">View all</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-danger">
                                        <i class="fas fa-exclamation-circle mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-medium">Application Error</h6>
                                    <p class="font-weight-light small-text">
                                        Just now
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="fas fa-wrench mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-medium">Settings</h6>
                                    <p class="font-weight-light small-text">
                                        Private message
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="far fa-envelope mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-medium">New user registration</h6>
                                    <p class="font-weight-light small-text">
                                        2 days ago
                                    </p>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#"
                            data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-envelope mx-0"></i>
                            <span class="count">25</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="messageDropdown">
                            <div class="dropdown-item">
                                <p class="mb-0 font-weight-normal float-left">You have 7 unread mails
                                </p>
                                <span class="badge badge-info badge-pill float-right">View all</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="images/faces/face4.jpg" alt="image" class="profile-pic">
                                </div>
                                <div class="preview-item-content flex-grow">
                                    <h6 class="preview-subject ellipsis font-weight-medium">David Grey
                                        <span class="float-right font-weight-light small-text">1 Minutes ago</span>
                                    </h6>
                                    <p class="font-weight-light small-text">
                                        The meeting is cancelled
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="images/faces/face2.jpg" alt="image" class="profile-pic">
                                </div>
                                <div class="preview-item-content flex-grow">
                                    <h6 class="preview-subject ellipsis font-weight-medium">Tim Cook
                                        <span class="float-right font-weight-light small-text">15 Minutes ago</span>
                                    </h6>
                                    <p class="font-weight-light small-text">
                                        New product launch
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <img src="images/faces/face3.jpg" alt="image" class="profile-pic">
                                </div>
                                <div class="preview-item-content flex-grow">
                                    <h6 class="preview-subject ellipsis font-weight-medium"> Johnson
                                        <span class="float-right font-weight-light small-text">18 Minutes ago</span>
                                    </h6>
                                    <p class="font-weight-light small-text">
                                        Upcoming board meeting
                                    </p>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                            id="profileDropdown">
                            <img src="images/faces/face5.jpg" alt="profile" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a class="dropdown-item">
                                <i class="fas fa-cog text-primary"></i>
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">
                                <i class="fas fa-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                    <li class="nav-item nav-settings d-none d-lg-block">
                        <a class="nav-link" href="#">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="fas fa-bars"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.html -->
            <div class="theme-setting-wrapper">
                <div id="settings-trigger"><i class="fas fa-fill-drip"></i></div>
                <div id="theme-settings" class="settings-panel">
                    <i class="settings-close fa fa-times"></i>
                    @if (Route::currentRouteName() == 'ventas.index')
                        <p class="settings-heading">Opciones para Ventas</p>
                    @else
                        <p class="settings-heading">Opciones para Cotizaciónes</p>
                    @endif
                    <!-- Sidebar -->
                    <div id="sidebar-coti" class="d-flex flex-column align-items-start p-3">
                        <h5 class="text-center w-100 mb-4 settings-heading">📋 Panel de Opciones</h5>

                        <!-- Productos -->
                        <button class="btn border w-100 mb-2  settings-heading "
                            onclick="$('#right-sidebar,#theme-settings').removeClass('open');" data-toggle="modal"
                            data-target="#modalCotizacion">
                            <i class="fas fa-box"></i> Productos
                        </button>

                        <!-- Clientes -->
                        <button class="btn border w-100 mb-2 settings-heading">
                            <i class="fas fa-users"></i> Clientes
                        </button>

                        <!-- Separador -->
                        <hr class="bg-light w-100">

                        <!-- Generar Imagen -->
                        <button class="btn btn-success w-100  settings-heading" id="generar-imagen">
                            <i class="fas fa-image"></i> Generar Imagen
                        </button>
                    </div>
                </div>
            </div>

            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <div class="nav-link">
                            <div class="profile-image">
                                <img src="images/faces/face5.jpg" alt="image" />
                            </div>
                            <div class="profile-name">
                                <p class="name">
                                    Welcome Jane
                                </p>
                                <p class="designation">
                                    Super Admin
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fa fa-home menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cotizacion.index') }}">
                            <i class="fas fa-file-invoice menu-icon"></i>
                            <span class="menu-title">Cotizaciones</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ventas.index') }}">
                            <i class="fas fa-shopping-cart menu-icon"></i>
                            <span class="menu-title">Ventas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('compras.index') }}">
                            <i class="fas fa-credit-card menu-icon"></i>
                            <span class="menu-title">Compras</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('productos.index') }}">
                            <i class="fas fa-tags menu-icon"></i>
                            <span class="menu-title">Productos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('proveedores.index') }}">
                            <i class="fas fa-industry menu-icon"></i>
                            <span class="menu-title">Proveedores</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('almaceninterno.index') }}">
                            <i class="fas fa-warehouse  menu-icon"></i>
                            <span class="menu-title">Almacen Interno</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cuentasbancarias.index') }}">
                            <i class="fas fa-university menu-icon"></i>
                            <span class="menu-title">Cuentas Bancarias</span>
                        </a>
                    </li>




                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">



                @yield('pagina')

                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025
                            Todos los Derechos Reservados.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hecho desde cero y
                            desarrollado con <i class="far fa-heart text-danger"></i></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>


            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{ asset('melody/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('melody/vendor.bundle.addons.js') }}"></script>
    @yield('scripts')



</body>


</html>
