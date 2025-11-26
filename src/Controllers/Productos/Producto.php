<?php

namespace Controllers\Productos;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Productos\Productos as DaoProductos;

class Producto extends PublicController
{
    private $viewData = [];

    public function run(): void
    {
        if (!isset($_GET["id"])) {
            die("Producto no especificado.");
        }

        $id = intval($_GET["id"]);
        $producto = DaoProductos::getProductoById($id);

        if (!$producto) {
            die("Producto no encontrado.");
        }


        $this->viewData = $producto;
        $this->viewData["stock_mayor_cero"] = ($producto["stock"] > 0);

        Renderer::render("productos/producto", $this->viewData);
    }
}
