<div class="container mt-4">
    <h1>{{nombre}}</h1>

    <div class="row">
        <div class="col-md-6">

            {{if imagen}}
            <img src="{{imagen}}" alt="{{nombre}}" class="img-fluid">
            {{endif imagen}}

            {{ifnot imagen}}
            <div class="producto-img-placeholder">Sin imagen</div>
            {{endifnot imagen}}
        </div>
        

        <div class="col-md-6">
            <h4>Categoría: {{categoria_nombre}}</h4>
            <p>{{descripcion}}</p>
            <h3 class="text-primary">$ {{precio}}</h3>

            {{if stock_mayor_cero}}
            <span class="badge bg-success">En stock</span>
            {{endif stock_mayor_cero}}

            {{ifnot stock_mayor_cero}}
            <span class="badge bg-danger">Agotado</span>
            {{endifnot stock_mayor_cero}}

            <br><br>
            <a href="index.php?page=Productos_Productos" class="btn btn-secondary">Volver al catálogo</a>
        </div>
    </div>
</div>
