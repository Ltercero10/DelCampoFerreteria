<?php

namespace Controllers\Admin;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Security\Security;

class Usuarios extends PrivateController
{
    public function run(): void
    {
        $viewData = array();
        $viewData["usuarios"] = Security::getUsuarios();
        Renderer::render("admin/usuarios", $viewData);
    }
}
