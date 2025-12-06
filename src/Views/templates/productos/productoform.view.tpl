<div class="container mt-4">
    <h2>{{modeDesc}}</h2>

    {{if hasErrors}}
    <div class="alert alert-danger">
        <ul>
            {{foreach errors}}
            {{foreach .}}
            <li>{{.}}</li>
            {{endfor}}
            {{endfor errors}}
        </ul>
    </div>
    {{endif}}

    <form method="POST" action="index.php?page=Productos_Producto">

        <input type="hidden" name="id" value="{{id}}">
        <input type="hidden" name="mode" value="{{mode}}">
        <input type="hidden" name="crf_token" value="{{crf_token}}">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{nombre}}" {{isReadOnly}}>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" {{isReadOnly}}>{{descripcion}}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" name="precio" value="{{precio}}" {{isReadOnly}}>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" value="{{stock}}" {{isReadOnly}}>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado" {{isReadOnly}}>
                <option value="ACT" {{if estado == "ACT"}}selected{{endif}}>Activo</option>
                <option value="INA" {{if estado == "INA"}}selected{{endif}}>Inactivo</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select class="form-select" name="categoria_id" {{isReadOnly}}>
                {{foreach categorias}}
                <option value="{{id}}" {{if ../categoria_id == id}}selected{{endif}}>
                    {{nombre}}
                </option>
                {{endfor categorias}}
            </select>
        </div>

        {{if showAction}}
        <button type="submit" class="btn {{actionBtnClass}}">
            {{actionBtnText}}
        </button>
        {{endif}}

        <a href="index.php?page=Productos_Productos" class="btn btn-secondary ms-2">
            Cancelar
        </a>

        <a href="index.php?page=cartview">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-count">{{default ~CART_ITEMS 0}}</span>
</a>

    </form>
</div>
