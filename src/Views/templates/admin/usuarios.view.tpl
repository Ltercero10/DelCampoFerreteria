<section class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Usuarios</h2>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Correo</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{foreach usuarios}}
                        <tr>
                            <td class="ps-3">{{usercod}}</td>
                            <td>{{useremail}}</td>
                            <td>{{username}}</td>
                            <td><span class="badge bg-info text-dark">{{usertipo}}</span></td>
                            <td>{{userest}}</td>
                            <td class="text-center">
                                <a href="index.php?page=Admin_UsuarioRoles&usercod={{usercod}}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-user-cog"></i> Roles
                                </a>
                            </td>
                        </tr>
                        {{endfor usuarios}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>