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

                <ul class="navbar-nav navbar-nav-right">




                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                          <img src="https://cdn-icons-png.flaticon.com/512/4140/4140037.png"
                                alt="Avatar con celular" />
                            @if (Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto del usuario"
                                    width="100" class="img-thumbnail">
                            @else
                                <img src="https://cdn-icons-png.flaticon.com/512/4140/4140037.png"
                                    alt="Avatar con celular" />
                            @endif

                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            @hasrole('admin')
                                <button class="dropdown-item" type="button" data-toggle="modal" style="cursor: pointer"
                                    data-target="#modalTelefonos">
                                    <i class="fas fa-cog text-primary"></i> Configuraciones
                                </button>
                            @endhasrole
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('usuarios.logout') }}" method="get">
                                <button type="submit" class="dropdown-item" style="cursor: pointer;">
                                    <i class="fas fa-power-off text-primary"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
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
                @if (Route::currentRouteName() == 'cotizacion.index')
                    <div id="settings-trigger" style="cursor: pointer"><i class="fas fa-sliders-h"></i> </div>
                @endif
                <div id="theme-settings" class="settings-panel">
                    <i class="settings-close fa fa-times"></i>

                    <p class="settings-heading">Opciones para Cotizaciónes</p>

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
                        <button class="btn border w-100 mb-2 settings-heading"
                            onclick="$('#right-sidebar,#theme-settings').removeClass('open');" data-toggle="modal"
                            data-target="#modalCotizacionCliente">
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
                                <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png"
                                    alt="Icono celular tecnología" width="100" />
                            </div>

                            <div class="profile-name">
                                <p class="name">Bienvenido</p>
                                <p class="designation">{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                    </li>

                    @hasanyrole('admin|vendedor')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.admin') }}">
                                <i class="fa fa-home menu-icon"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                    @endhasanyrole
                    @hasanyrole('admin') <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.reportes') }}">
                                <i class="fas fa-chart-bar menu-icon"></i>
                                <span class="menu-title">Reporte Ventas</span>
                            </a>
                            </li>
                        @endhasanyrole

                        @hasrole('admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('usuarios.index') }}">
                                    <i class="fas fa-user-shield menu-icon"></i>
                                    <span class="menu-title">Roles y Permisos</span>
                                </a>
                            </li>
                        @endhasrole

                        @hasanyrole('admin|vendedor')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('caja.index') }}">
                                    <i class="fas fa-money-bill-wave menu-icon"></i>
                                    <span class="menu-title">Caja</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cotizacion.index') }}">
                                    <i class="fas fa-file-invoice menu-icon"></i>
                                    <span class="menu-title">Cotizaciones</span>
                                </a>
                            </li>
                        @endhasanyrole
                        @hasanyrole('admin|vendedor|almacenero')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ventas.index') }}">
                                    <i class="fas fa-shopping-cart menu-icon"></i>
                                    <span class="menu-title">Ventas</span>
                                </a>
                            </li>
                        @endhasanyrole
                        @hasanyrole('admin|vendedor')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cuentas.index') }}">
                                    <i class="fas fa-wallet menu-icon"></i>
                                    <span class="menu-title">Cuentas Clientes</span>
                                </a>
                            </li>
                        @endhasanyrole

                        <<<<<<< Updated upstream
                            @hasanyrole('admin|almacenero')=======@hasanyrole('admin|almaceneroventa|almacenero')>>>>>>>
                            Stashed changes
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('compras.index') }}">
                                    <i class="fas fa-credit-card menu-icon"></i>
                                    <span class="menu-title">Compras</span>
                                </a>
                            </li>
                        @endhasanyrole

                        @hasrole('admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pagos.index') }}">
                                    <i class="fas fa-wallet menu-icon"></i>
                                    <span class="menu-title">Pagos</span>
                                </a>
                            </li>
                        @endhasrole

                        @hasanyrole('admin|vendedor|almacenero')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('productos.index') }}">
                                    <i class="fas fa-tags menu-icon"></i>
                                    <span class="menu-title">Productos</span>
                                </a>
                            </li>
                        @endhasanyrole

                        @hasanyrole('admin|almacenero')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('proveedores.index') }}">
                                    <i class="fas fa-industry menu-icon"></i>
                                    <span class="menu-title">Proveedores</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('almaceninterno.index') }}">
                                    <i class="fas fa-warehouse menu-icon"></i>
                                    <span class="menu-title">Almacen Interno</span>
                                </a>
                            </li>
                        @endhasanyrole

                        @hasanyrole('admin|vendedor|almacenero')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cuentasbancarias.index') }}">
                                    <i class="fas fa-university menu-icon"></i>
                                    <span class="menu-title">Cuentas Bancarias</span>
                                </a>
                            </li>
                        @endhasanyrole
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



<div class="modal fade" id="modalTelefonos" tabindex="-1" aria-labelledby="modalTelefonosLabel"
    aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTelefonosLabel"><i class="fas fa-phone-alt"></i> Contactos
                    Telefónicos de la Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="telefono1">
                        <i class="fas fa-phone text-info mr-2"></i> Número de teléfono 1
                    </label>
                    <input type="tel" class="form-control" id="telefono1" name="telefono1" maxlength="9"
                        required pattern="\d{9}" placeholder="Ej. 987654321">
                    <small class="form-text text-muted">Ingrese un número válido de 9 dígitos.</small>
                </div>

                <div class="form-group mt-3">
                    <label for="telefono2">
                        <i class="fas fa-phone text-info mr-2"></i> Número de teléfono 2
                    </label>
                    <input type="tel" class="form-control" id="telefono2" name="telefono2" maxlength="9"
                        pattern="\d{9}" placeholder="Ej. 912345678">
                    <small class="form-text text-muted">Opcional. Solo si desea registrar un segundo
                        número.</small>
                </div>
            </div>

            <div class="modal-footer justify-content-between">

                <button type="button" class="btn btn-success" onclick="guardarTelefonos()">
                    <i class="fas fa-save mr-1"></i> Guardar
                </button>
            </div>

        </div>

    </div>
</div>
<!-- plugins:js -->
<script src="{{ asset('melody/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('melody/vendor.bundle.addons.js') }}"></script>
<script>
    function guardarTelefonos() {
        const telefono1 = document.getElementById('telefono1').value.trim();
        const telefono2 = document.getElementById('telefono2').value.trim();

        if (!telefono1.match(/^\d{9}$/)) {
            alert('Teléfono 1 debe tener 9 dígitos');
            return;
        }

        if (telefono2 && !telefono2.match(/^\d{9}$/)) {
            alert('Teléfono 2 debe tener 9 dígitos');
            return;
        }

        fetch('/configuraciones', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    telefono1: telefono1,
                    telefono2: telefono2
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Teléfonos guardados correctamente');
                    $('#modalTelefonos').modal('hide');
                } else {
                    alert('Error al guardar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error en la solicitud');
            });
    }
</script>

@yield('scripts')



</body>


</html>
