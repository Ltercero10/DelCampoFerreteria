<?php

namespace Controllers;

use Utilities\Security;
use Utilities\Cart\CartFns;
use Utilities\Context;
use Views\Renderer;
use Dao\Cart\Cart;

class cartsview extends PublicController
{
    public function run(): void
    {
        /* ────────────────────────────────────────────────
           1. Si el usuario envía POST para eliminar item
        ─────────────────────────────────────────────────── */
        if ($this->isPostBack() && isset($_POST["deleteItem"])) {

            $productId = intval($_POST["productId"]);

            if (Security::isLogged()) {
                Cart::deleteFromAuthCart(Security::getUserId(), $productId);
            } else {
                $anon = CartFns::getAnnonCartCode();
                Cart::deleteFromAnonCart($anon, $productId);
            }

            // 🔄 Refrescar contador del carrito
            $this->updateCartCounter();

            // Recargar página para evitar repost
            header("Location: index.php?page=cartview");
            exit;
        }

        /* ────────────────────────────────────────────────
           2. Obtener carrito actual
        ─────────────────────────────────────────────────── */
        if (Security::isLogged()) {
            $cart = Cart::getAuthCart(Security::getUserId());
        } else {
            $anon = CartFns::getAnnonCartCode();
            $cart = Cart::getAnonCart($anon);
        }

        /* ────────────────────────────────────────────────
           3. Calcular subtotales y total
        ─────────────────────────────────────────────────── */
        $total = 0;

        foreach ($cart as &$item) {
            $item["subtotal"] = $item["crrprc"] * $item["crrctd"];
            $total += $item["subtotal"];
        }

        /* ────────────────────────────────────────────────
           4. Enviar datos a la vista
        ─────────────────────────────────────────────────── */
        $viewData = [
            "items" => $cart,
            "total" => number_format($total, 2)
        ];

        Renderer::render("cart1/cartview", $viewData);
    }

    /* ────────────────────────────────────────────────
       Función para actualizar el contexto CART_ITEMS
    ─────────────────────────────────────────────────── */
    private function updateCartCounter()
    {
        if (Security::isLogged()) {
            $items = Cart::getAuthCart(Security::getUserId());
        } else {
            $anon = CartFns::getAnnonCartCode();
            $items = Cart::getAnonCart($anon);
        }

        Context::setContext("CART_ITEMS", count($items));
    }
}
