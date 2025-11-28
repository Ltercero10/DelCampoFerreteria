<div class="container mt-4">

    
    <style>
        .img-producto {
            width: 500px;
            height: 500px;
            object-fit: cover; 
            border-radius: 10px;
            
            background: #f9f9f9;
        }

        .producto-img-placeholder {
            width: 500px;
            height: 500px;
            border: 1px solid #ccc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            color: #666;
            font-size: 18px;
        }
    </style>

    <h1>{{nombre}}</h1>

    <div class="row">
        <div class="col-md-6 text-center">

            {{if imagen}}
            <img src="data:image/jpeg;base64,{{imagen}}" 
                 alt="{{nombre}}" 
                 class="img-producto">
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

        <div class="mt-4">
            <h5>Subir nueva imagen</h5>
            <form action="index.php?page=Productos_Producto&id={{id}}" method="POST" enctype="multipart/form-data">
                <input type="file" name="imagen" accept="image/*" required />
                <button type="submit" class="btn btn-primary btn-sm">Subir imagen</button>
            </form>
        </div>

    </div>
</div>
