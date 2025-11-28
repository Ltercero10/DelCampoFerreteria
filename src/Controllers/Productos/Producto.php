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

        // Si está enviando el formulario
        if ($this->isPostBack() && isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {

            $tmpFile = $_FILES["imagen"]["tmp_name"];
            $imagenBase64 = base64_encode(file_get_contents($tmpFile));

            // Guardar en la BD
            DaoProductos::actualizarProductoImagen($id, $imagenBase64);

            // Recargar el producto actualizado
            $producto["imagen"] = $imagenBase64;
        }

        $this->viewData = $producto;
        $this->viewData["stock_mayor_cero"] = ($producto["stock"] > 0);

        Renderer::render("productos/producto", $this->viewData);
    }
}
