<div class="container mt-4">
    <h1>Catálogo de Productos</h1>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="index.php" class="row g-3">
                <input type="hidden" name="page" value="Productos_Productos">

                <div class="col-md-4">
                    <label for="partialName" class="form-label">Buscar por nombre</label>
                    <input type="text" class="form-control" id="partialName" name="partialName" value="{{partialName}}"
                        placeholder="Nombre del producto">
                </div>

                <div class="col-md-4">
                    <label for="categoriaId" class="form-label">Categoría</label>
                    <select class="form-select" id="categoriaId" name="categoriaId">
                        <option value="">Todas las categorías</option>
                        {{foreach categorias}}
                        <option value="{{id}}">
                            {{nombre}}
                        </option>
                        {{endfor categorias}}
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Listado de productos -->
    <div class="productos-lista">

        {{foreach productos}}
        <div class="producto-card">

            {{if imagen}}
            <img src="data:image/jpeg;base64,{{imagen}}" alt="{{nombre}}" class="img-fluid">
            {{endif imagen}}

            {{ifnot imagen}}
            <div class="producto-img-placeholder">Sin imagen</div>
            {{endifnot imagen}}


            <h2>{{nombre}}</h2>
            <h4>{{categoria_nombre}}</h4>

            <p>{{descripcion}}</p>

            <p class="precio">${{precio}}</p>

            <!-- Estado de Stock -->
            {{if stock_mayor_cero}}
            <span class="badge badge-success">En stock</span>
            {{endif stock_mayor_cero}}

            {{ifnot stock_mayor_cero}}
            <span class="badge badge-danger">Agotado</span>
            {{endifnot stock_mayor_cero}}

            <a href="index.php?page=Productos_Producto&id={{id}}">
                Ver detalles
            </a>

        </div>
        {{endfor productos}}

    </div>

    <!-- Paginación -->
    {{if pages > 1}}
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            {{if pageNum > 1}}
            <li class="page-item">
                <a class="page-link"
                    href="index.php?page=Productos_Productos&pageNum={{pageNum-1}}&partialName={{partialName}}&categoriaId={{categoriaId}}">
                    Anterior
                </a>
            </li>
            {{endif}}

            {{for i in 1..pages}}
            <li class="page-item {{if i == pageNum}}active{{endif}}">
                <a class="page-link"
                    href="index.php?page=Productos_Productos&pageNum={{i}}&partialName={{partialName}}&categoriaId={{categoriaId}}">
                    {{i}}
                </a>
            </li>
            {{endfor}}

            {{if pageNum < pages}} <li class="page-item">
                <a class="page-link"
                    href="index.php?page=Productos_Productos&pageNum={{pageNum+1}}&partialName={{partialName}}&categoriaId={{categoriaId}}">
                    Siguiente
                </a>
                </li>
                {{endif}}
        </ul>
    </nav>
    {{endif}}
</div>