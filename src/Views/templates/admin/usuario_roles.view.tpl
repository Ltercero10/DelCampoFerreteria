<section class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Asignar Roles</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold">Usuario:</label>
                        <span class="fs-5 ms-2">{{username}}</span>
                    </div>
                    <div class="mb-4">
                        <label class="fw-bold">Correo:</label>
                        <span class="text-muted ms-2">{{useremail}}</span>
                    </div>
                    
                    <hr>
                    
                    <form action="index.php?page=Admin_UsuarioRoles&usercod={{usercod}}" method="POST">
                        <h5 class="mb-3">Roles Disponibles</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="15%">Estado</th>
                                        <th>Rol</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{foreach roles}}
                                    <tr>
                                        <td class="text-center align-middle">
                                            <div class="form-check d-flex justify-content-center">
                                                <!-- Checkbox más grande -->
                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{rolescod}}" {{assigned}} style="transform: scale(1.3);">
                                            </div>
                                        </td>
                                        <td class="align-middle fw-bold">{{rolescod}}</td>
                                        <td class="align-middle">{{rolesdsc}}</td>
                                    </tr>
                                    {{endfor roles}}
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="index.php?page=Admin_Usuarios" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>