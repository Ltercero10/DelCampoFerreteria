<?php

namespace Controllers\Checkout;

use Controllers\PublicController;
use Utilities\Security;
use Dao\Transactions;

class Historial extends PublicController
{
    public function run(): void
    {
        if (!Security::isLogged()) {
            \Utilities\Site::redirectTo("index.php?page=sec_login");
            return;
        }

        $userId = Security::getUserId();
        $isAdmin = Security::isInRol($userId, 'ADMIN');


        if ($isAdmin) {
            $transacciones = Transactions::getAll();
        } else {
            $transacciones = Transactions::getByUser($userId);
        }

        $html = "";

        if (!empty($transacciones)) {
            foreach ($transacciones as $trans) {

                $fecha = "N/A";
                if (!empty($trans['fecha'])) {
                    $fecha = date("d/m/Y h:i A", strtotime($trans['fecha']));
                }

                $monto = number_format((float)($trans['monto'] ?? 0), 2);
                $estado = ucfirst($trans['estado'] ?? 'pendiente');

                $productosHtml = "Sin productos";
                if (!empty($trans['productos'])) {
                    $productos = json_decode($trans['productos'], true);
                    if (is_array($productos) && count($productos) > 0) {
                        $productosHtml = "<ul style='margin:0; padding-left:15px;'>";
                        foreach ($productos as $p) {
                            $nombre   = $p['nombre'] ?? 'Producto';
                            $cantidad = $p['cantidad'] ?? 0;
                            $precio   = number_format((float)($p['precio'] ?? 0), 2);
                            $productosHtml .= "<li>{$nombre} ({$cantidad}) - L.{$precio}</li>";
                        }
                        $productosHtml .= "</ul>";
                    }
                }


                $html .= "<tr>";
                $html .= "<td>" . ($trans['id'] ?? 'N/A') . "</td>";

                if ($isAdmin) {
                    $email = $trans['useremail'] ?? 'N/A';
                    $html .= "<td><strong>{$email}</strong></td>";
                }

                $html .= "<td>{$fecha}</td>";
                $html .= "<td>L. {$monto}</td>";
                $html .= "<td>{$estado}</td>";
                $html .= "<td>{$productosHtml}</td>";
                $html .= "</tr>";
            }
        }

        $viewData = [
            "tabla_transacciones" => $html,
            "total_transacciones" => count($transacciones),
            "is_admin" => $isAdmin
        ];

        \Views\Renderer::render("historial", $viewData);
    }
}
