@extends('partials.layout')
@section('estilos')
@endsection
@section('pagina')
    <div class="content-wrapper">
        @component('componentes.com_titulo', [
            'titulo' => 'Usuarios y Permisos',
            'paginaprincipal' => 'Usuarios',
            'paginaactual' => 'Roles y Permisos',
        ])
        @endcomponent
        <div class="container">
            <div class="bg-orange p-3 mb-2">
                <h5 class=""><i class="fas fa-users-cog"></i> Gestión de Usuarios y Roles</h5>
            </div>

            @if (session('success'))
                <div class="alert alert-success mb-2 mt-2 msj">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->has('error'))
                <div class="alert alert-danger mb-2 mt-2 msj">
                    {{ $errors->first('error') }}
                </div>
            @endif
            <div class="row">
                <!-- Lista de Usuarios -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Usuarios</div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuarios as $usuario)
                                        <tr>
                                            <td>{{ $usuario->name }}</td>
                                            <td>{{ $usuario->email }}</td>
                                            <td>{{ $usuario->rol ?? 'Sin rol' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning"
                                                    onclick="editarUsuario({{ $usuario }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Eliminar usuario?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Formulario Crear/Editar Usuario -->
                <div class="col-md-5">
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">Agregar/Editar Usuario</div>
                        <div class="card-body">
                            <form method="POST" id="formUsuario" action="{{ route('usuarios.store') }}">
                                @csrf
                                <input type="hidden" name="id" id="usuario_id">
                                <div class="mb-2">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="mb-2">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                                <div class="mb-2">
                                    <label>Rol</label>
                                    <select class="form-control" name="role" id="role" required>
                                        <option value="">Seleccione</option>
                                        <option value="admin">Admin</option>
                                        <option value="vendedor">Vendedor</option>
                                        <option value="almacenero">Almacenero</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label>Contraseña</label>
                                    <input type="password" class="form-control" name="password" >
                                </div>
                                <button type="submit" class="btn bg-orange">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            
            @foreach ($roles as $rol)
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <strong>{{ ucfirst($rol->name) }}</strong>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($permisos as $permiso)
                    @if ($rol->hasPermissionTo($permiso->name))
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="permiso_{{ $rol->id }}_{{ $permiso->id }}"
                                       checked
                                       disabled>
                                <label class="form-check-label" for="permiso_{{ $rol->id }}_{{ $permiso->id }}">
                                    {{ $permiso->name }}
                                </label>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endforeach

        </div>

    </div>
@endsection
@section('scripts')
    <script>
        if ($(".msj").length) {
            setTimeout(() => {
                $(".msj").fadeOut();

            }, 3000);
        }

        function editarUsuario(usuario) {
            document.getElementById('usuario_id').value = usuario.id;
            document.getElementById('name').value = usuario.name;
            document.getElementById('email').value = usuario.email;
            document.getElementById('role').value = usuario.rol ?? '';
        }
    </script>
@endsection
