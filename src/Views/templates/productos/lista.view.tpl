<div class="container mt-4">
    <h1>Catálogo de Productos</h1>

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
                <div class="col-md-2 d-flex align-items-end">
                    <a href="index.php?page=Productos_Producto&mode=INS" class="btn btn-primary">Crear</a>
                </div>
            </form>
        </div>
    </div>

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

            {{if stock_mayor_cero}}
            <span class="badge bg-success">En stock ({{stock}})</span>
            {{endif stock_mayor_cero}}

            {{ifnot stock_mayor_cero}}
            <span class="badge bg-danger">Agotado</span>
            {{endifnot stock_mayor_cero}}

            <br>


            <a href="index.php?page=Productos_Producto&mode=DSP&id={{id}}">
                Ver detalles
            </a>

            <a href="index.php?page=Productos_Producto&mode=UPD&id={{id}}" class="btn btn-warning btn-sm">
                Editar
            </a>

            <form action="index.php" method="GET" style="display: inline;"
                onsubmit="return confirm('¿Está seguro de eliminar el producto {{nombre}}?');">
                <input type="hidden" name="page" value="Productos_Producto">
                <input type="hidden" name="mode" value="DEL">
                <input type="hidden" name="id" value="{{id}}">
                <button type="submit" class="btn btn-danger btn-sm">
                    Eliminar
                </button>
            </form>

        </div>


        {{endfor productos}}

    </div>

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