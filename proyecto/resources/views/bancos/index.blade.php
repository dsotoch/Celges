@extends('partials.layout')
@section('estilos')
@endsection
@section('pagina')
    <div class="content-wrapper">
        @component('componentes.com_titulo', [
            'titulo' => 'Cuentas Bancarias',
            'paginaprincipal' => 'Cuentas',
            'paginaactual' => 'Mis Cuentas',
        ])
        @endcomponent

        <div class="card">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mensajes" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mensajes" role="alert">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ session('error') }}

                </div>
            @endif

            <div class="container mt-3">
                <div class="card shadow">
                    <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-university mr-2"></i> Cuentas Bancarias</h5>
                        <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalNuevaCuenta">
                            <i class="fas fa-plus-circle"></i> Nueva Cuenta
                        </button>
                    </div>
                    <div class="card-body">

                        <!-- Buscador -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Buscar:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="buscar"
                                    placeholder="Banco, titular, número de cuenta...">
                            </div>
                        </div>
                        <div class="alert alert-info d-flex align-items-center"
                            style="background-color: #bcdcff; color: #000;">
                            <i class="fas fa-info-circle mr-2"></i>
                            Las filas con fondo celeste indican cuentas inactivas.
                        </div>

                        <!-- Tabla de cuentas -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="tablaCuentas">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Banco</th>
                                        <th>Tipo</th>
                                        <th>Número</th>
                                        <th>CCI</th>
                                        <th>Titular</th>
                                        <th>Moneda</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí irán las cuentas dinámicas -->
                                    @foreach ($cuentas as $item)
                                        <tr class=" {{ $item->activo == '1' ? '' : 'bg-inactivo' }}">
                                            <td>{{ strtoupper($item->banco) }}</td>
                                            <td>{{ strtoupper($item->tipo_cuenta) }}</td>
                                            <td>{{ strtoupper($item->numero_cuenta) }}</td>
                                            <td>{{ strtoupper($item->cci) == '' ? '-' : $item->cci }}</td>
                                            <td>{{ strtoupper($item->titular) }}</td>
                                            <td>{{ $item->moneda == 'PEN' ? 'SOLES' : 'DOLARES' }}</td>
                                            <td class="text-center d-flex center gap-2 ">

                                                <form action="{{ route('cuentasbancarias.update', $item->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="activo"
                                                        value="{{ $item->activo == '1' ? '0' : '1' }}">

                                                    @if ($item->activo)
                                                        <button type="submit" class="btn btn-sm btn-warning"
                                                            title="Desactivar">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Activar">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                </form>

                                                <form action="{{ route('cuentasbancarias.destroy', ['id' => $item->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" title="Eliminar"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal Nueva Cuenta -->
            <div class="modal fade" id="modalNuevaCuenta" tabindex="-1" role="dialog"
                aria-labelledby="modalNuevaCuentaLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form class="modal-content" method="POST" action="{{ route('cuentasbancarias.store') }}">
                        @csrf
                        <div class="modal-header bg-orange text-white">
                            <h5 class="modal-title" id="modalNuevaCuentaLabel">
                                <i class="fas fa-plus-circle mr-2"></i> Registrar Nueva Cuenta
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <!-- Banco -->
                            <div class="form-group">
                                <label><i class="fas fa-university mr-1"></i> Banco</label>
                                <input type="text" name="banco"
                                    class="form-control @error('banco') is-invalid @enderror" value="{{ old('banco') }}"
                                    placeholder="Ej. BCP, BBVA, Interbank" required>
                                @error('banco')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo de Cuenta -->
                            <div class="form-group">
                                <label><i class="fas fa-list-alt mr-1"></i> Tipo de Cuenta</label>
                                @php use App\Enums\EnumTipoCuenta; @endphp
                                <select name="tipo_cuenta" class="form-control @error('tipo_cuenta') is-invalid @enderror"
                                    required>
                                    <option value="">-- Selecciona tipo de cuenta --</option>
                                    @foreach (EnumTipoCuenta::cases() as $tipo)
                                        <option value="{{ $tipo->value }}"
                                            {{ old('tipo_cuenta') === $tipo->value ? 'selected' : '' }}>
                                            {{ $tipo->value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_cuenta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Número de Cuenta -->
                            <div class="form-group">
                                <label><i class="fas fa-hashtag mr-1"></i> Número de Cuenta</label>
                                <input type="text" name="numero_cuenta"
                                    class="form-control @error('numero_cuenta') is-invalid @enderror"
                                    value="{{ old('numero_cuenta') }}" placeholder="Ej. 123-4567890123-00" required>
                                @error('numero_cuenta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Titular -->
                            <div class="form-group">
                                <label><i class="fas fa-user mr-1"></i> Titular</label>
                                <input type="text" name="titular"
                                    class="form-control @error('titular') is-invalid @enderror"
                                    value="{{ old('titular') }}" placeholder="Nombre del titular de la cuenta" required>
                                @error('titular')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Moneda -->
                            <div class="form-group">
                                <label><i class="fas fa-money-bill-wave mr-1"></i> Moneda</label>
                                <select name="moneda" class="form-control @error('moneda') is-invalid @enderror"
                                    required>
                                    <option value="">-- Selecciona moneda --</option>
                                    <option value="PEN" {{ old('moneda') === 'PEN' ? 'selected' : '' }}>S/ - Soles
                                    </option>
                                    <option value="USD" {{ old('moneda') === 'USD' ? 'selected' : '' }}>$ - Dólares
                                    </option>
                                </select>
                                @error('moneda')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById("buscar").addEventListener("keyup", function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaCuentas tbody tr");

            filas.forEach(function(fila) {
                let texto = fila.textContent.toLowerCase();
                fila.style.display = texto.includes(filtro) ? "" : "none";
            });
        });
        if ($(".mensajes").length) {
            setTimeout(() => {
                $(".mensajes").fadeOut();

            }, 3000);
        }
    </script>
    @if (session('show_modal'))
        <script>
            $(document).ready(function() {

                $('#modalNuevaCuenta').modal('show');

            });
        </script>
    @endif
    @if (session('show_modal_edit'))
        <script>
            $(document).ready(function() {
                $('#modalRecursoeditar').modal('show');

            });
        </script>
    @endif

    <script src="{{ asset('melody/data-table.js') }}"></script>
@endsection
