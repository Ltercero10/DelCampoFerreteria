<?php

namespace Controllers\Admin;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Security\Security;
use Utilities\Site;

class UsuarioRoles extends PrivateController
{
    public function run(): void
    {
        $usercod = isset($_GET["usercod"]) ? intval($_GET["usercod"]) : 0;

        if ($usercod === 0) {
            Site::redirectTo("index.php?page=Admin_Usuarios");
        }

        if ($this->isPostBack()) {
            $selectedRoles = isset($_POST["roles"]) ? $_POST["roles"] : array();
            $allRoles = Security::getAllRoles();

            foreach ($allRoles as $rol) {
                if (in_array($rol["rolescod"], $selectedRoles)) {
                    Security::addRoleToUser($usercod, $rol["rolescod"]);
                } else {
                    Security::removeRoleFromUser($usercod, $rol["rolescod"]);
                }
            }
            Site::redirectToWithMsg("index.php?page=Admin_UsuarioRoles&usercod=" . $usercod, "Roles Actualizados!");
        }

        $viewData = array();
        $viewData["usercod"] = $usercod;

        $usuario = Security::getUsuarios();
        foreach ($usuario as $u) {
            if ($u["usercod"] == $usercod) {
                $viewData["username"] = $u["username"];
                $viewData["useremail"] = $u["useremail"];
                break;
            }
        }


        $allRoles = Security::getAllRoles();
        $userRoles = Security::getRolesByUser($usercod);

        $userRolesSimple = array();
        foreach ($userRoles as $ur) {
            $userRolesSimple[] = $ur["rolescod"];
        }

        $rolesView = array();
        foreach ($allRoles as $rol) {
            $rol["assigned"] = in_array($rol["rolescod"], $userRolesSimple) ? "checked" : "";
            $rolesView[] = $rol;
        }
        $viewData["roles"] = $rolesView;

        Renderer::render("admin/usuario_roles", $viewData);
    }
}
