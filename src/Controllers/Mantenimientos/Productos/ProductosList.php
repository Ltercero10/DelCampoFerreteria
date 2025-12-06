<?php

namespace Controllers\Mantenimientos\Productos;

use Dao\Mantenimientos\Productos\Productos as ProductosDao;
use Controllers\PublicController;
use Views\Renderer;

class ProductosList extends PublicController
{
    public function run(): void
    {
        $viewData["rows"] = ProductosDao::getAll();
        Renderer::render("mantenimientos/productos/productos_list", $viewData);
    }
}
