<?php

namespace Controllers\Checkout;

use Controllers\PublicController;
use Utilities\Security;
use Dao\Cart\Cart as CartDao;
use Dao\Transactions;
use Views\Renderer;

class Capture extends PublicController
{
    public function run(): void
    {
        // 1. token de PayPal
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $this->showError("No se recibió confirmación de PayPal");
            return;
        }

        // 2. Verificar usuario
        if (!Security::isLogged()) {
            $this->showError("Debes iniciar sesión");
            return;
        }

        $userId = Security::getUserId();

        // 3. carrito y calcular total
        $cartItems = CartDao::getAuthCart($userId);

        if (empty($cartItems)) {
            $this->showError("Carrito vacío");
            return;
        }

        $total = 0;
        $productosDetalle = [];

        foreach ($cartItems as $item) {
            $precio = $item['crrprc'] ?? 0;
            $cantidad = $item['crrctd'] ?? 1;
            $subtotal = $precio * $cantidad;
            $total += $subtotal;

            $productosDetalle[] = [
                'nombre' => $item['productName'] ?? 'Producto',
                'precio' => $precio,
                'cantidad' => $cantidad,
                'subtotal' => $subtotal
            ];
        }

        // 4. Guarda en base de datos
        $transaccionGuardada = false;
        try {
            if (class_exists('Dao\Transactions') && method_exists('Dao\Transactions', 'save')) {
                $transaccionGuardada = Transactions::save(
                    $userId,
                    $token,
                    $total,
                    'completado',
                    $productosDetalle
                );
            }
        } catch (\Exception $e) {
            error_log("Error guardando transacción: " . $e->getMessage());
        }

        // 5. Se limpia carrito
        try {
            if (method_exists('Dao\Cart\Cart', 'removeAllFromAuthCart')) {
                CartDao::removeAllFromAuthCart($userId);
            }
        } catch (\Exception $e) {
            error_log("Error vaciando carrito: " . $e->getMessage());
        }

        // 6.  datos para la vista
        $viewData = [
            'success' => true,
            'order_id' => $token,
            'total' => number_format($total, 2),
            'fecha' => date('d/m/Y H:i:s'),
            'productos' => $productosDetalle,
            'transaccion_guardada' => $transaccionGuardada
        ];

        // 7. Renderizar vista
        Renderer::render("paypal/accept", $viewData);
    }

    private function showError($mensaje)
    {
        $viewData = [
            'success' => false,
            'mensaje' => $mensaje
        ];
        Renderer::render("paypal/error", $viewData);
    }
}
