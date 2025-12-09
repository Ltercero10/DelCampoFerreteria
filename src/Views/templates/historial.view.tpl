<div class="container mt-4">
    <h1 class="mb-4">Historial de Pagos</h1>

    <div class="alert alert-info">
        <strong>Total de transacciones:</strong> {{total_transacciones}}
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    {{if is_admin}}
                    <th>Usuario</th>
                    {{endif is_admin}}
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Productos</th>
                </tr>
            </thead>
            <tbody>
                {{tabla_transacciones}}
            </tbody>
        </table>
    </div>
</div>