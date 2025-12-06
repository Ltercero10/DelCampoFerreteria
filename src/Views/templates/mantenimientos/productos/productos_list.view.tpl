<div class="container mt-4">
    <div class="justify-content-between align-items-center mb-4">

        <h1 class="mb-4">Catálogo de Productos</h1>
        <a href="index.php?page=Mantenimientos_Productos_ProductosForm&mode=INS" class="btn btn-primary">
            ➕ Agregar Nuevo Producto
        </a>

    </div>


    <div class="row g-4">
        {{foreach rows}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="position-relative">

                    <img src="{{productImgUrl}}" class="card-img-top" alt="{{productName}}"
                        style="height: 200px; object-fit: cover;">

                </div>

                <div class="card-body">
                    <small class="text-muted d-block mb-1">#{{productId}}</small>
                    <h6 class="card-title mb-2">{{productName}}</h6>


                    <p class="card-text text-muted small mb-3"
                        style="min-height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{productDescription}}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="h5 fw-bold text-primary mb-0">${{productPrice}}</span>
                    </div>

                    <div class="mb-2">
                        <span class="badge bg-secondary">Stock: {{productStock}}</span>
                    </div>


                </div>
                <div>
                    <form action="index.php?page=Mantenimientos_Productos_ProductosList" method="POST">
                        <input type="hidden" name="productId" value="{{productId}}">
                        <button type="submit" name="addTocart" class="btn btn-sm btn-outline-primary add-to-cart">Agregar al carrito</button>
                    </form>
                </div>

                <div class="card-footer bg-white border-top-0 pt-0">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?page=Mantenimientos_Productos_ProductosForm&mode=DSP&productId={{productId}}"
                            class="btn btn-sm btn-outline-primary">
                            Ver
                        </a>
                        <a href="index.php?page=Mantenimientos_Productos_ProductosForm&mode=UPD&productId={{productId}}"
                            class="btn btn-sm btn-outline-warning">
                            Editar
                        </a>
                        <a href="index.php?page=Mantenimientos_Productos_ProductosForm&mode=DEL&productId={{productId}}"
                            class="btn btn-sm btn-outline-danger">
                            Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{endfor rows}}
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .card-img-top {
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }
</style>