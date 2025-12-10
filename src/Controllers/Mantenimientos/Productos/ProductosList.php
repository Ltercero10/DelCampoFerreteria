<?php

namespace Controllers\Mantenimientos\Productos;

use Controllers\PrivateController;
use Dao\Mantenimientos\Productos\Productos as ProductosDao;
use Controllers\PublicController;
use Views\Renderer;

class ProductosList extends PrivateController
{
    public function run(): void
    {
        $viewData["rows"] = ProductosDao::getAll();

        $viewData["is_admin"] = \Utilities\Security::isInRol(\Utilities\Security::getUserId(), 'ADMIN');

        Renderer::render("mantenimientos/productos/productos_list", $viewData);
    }
}
