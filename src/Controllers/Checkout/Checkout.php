<?php

namespace Controllers\Checkout;

use Controllers\PublicController;
use Utilities\Security;
use Utilities\Cart\CartFns;
use Dao\Cart\Cart as CartDao;
use Utilities\Site;
use Views\Renderer;
use Utilities\Context;

class Checkout extends PublicController
{
    public function run(): void
    {

        if (!\Utilities\Security::isLogged()) {
            \Utilities\Site::redirectTo("index.php?page=Sec_Login&redirto=" . urlencode("index.php?page=Checkout_Checkout"));
            return;
        }


        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }


        if (Security::isLogged()) {
            $usercod = Security::getUserId();
            $items   = CartDao::getAuthCart($usercod);
        } else {
            $anonCod = CartFns::getAnnonCartCode();
            $items   = CartDao::getAnonCart($anonCod);
        }


        if ($this->isPostBack()) {
            $productId = intval($_POST['productId'] ?? 0);

            if (isset($_POST['increase'])) {
                foreach ($items as $i) {
                    if ($i['productId'] == $productId) {
                        $price = $i['crrprc'];
                        break;
                    }
                }
                if (Security::isLogged()) {
                    CartDao::addToAuthCart($productId, $usercod, 1, $price);
                } else {
                    CartDao::addToAnonCart($productId, $anonCod, 1, $price);
                }
                Site::redirectTo('index.php?page=Checkout_Checkout');
                die();
            }

            if (isset($_POST['decrease'])) {
                $productId = intval($_POST['productId'] ?? 0);
                if (Security::isLogged()) {
                    CartDao::decreaseAuthCartItem($usercod, $productId);
                } else {
                    CartDao::decreaseAnonCartItem($anonCod, $productId);
                }
                Site::redirectTo('index.php?page=Checkout_Checkout');
                die();
            }


            if (isset($_POST['empty_cart'])) {
                if (Security::isLogged()) {
                    CartDao::removeAllFromAuthCart($usercod);
                } else {
                    CartDao::removeAllFromAnonCart($anonCod);
                }
                Site::redirectTo('index.php?page=Checkout_Checkout');
                die();
            }


            if (isset($_POST['pay_with_paypal'])) {

                if (empty($items)) {
                    Site::redirectTo('index.php?page=Checkout_Checkout&error=empty_cart');
                    die();
                }


                $subTotal = 0;
                $cartSummary = [];
                foreach ($items as $item) {
                    $itemTotal = $item['crrprc'] * $item['crrctd'];
                    $subTotal += $itemTotal;

                    $cartSummary[] = [
                        'productId' => $item['productId'] ?? $item['productcod'] ?? 0,
                        'name' => $item['productName'] ?? 'Producto',
                        'price' => $item['crrprc'],
                        'quantity' => $item['crrctd'],
                        'total' => $itemTotal
                    ];
                }

                if ($subTotal <= 0) {
                    Site::redirectTo('index.php?page=Checkout_Checkout&error=invalid_total');
                    die();
                }

                try {
                    // Crear instancia de PayPal
                    $PayPalRestApi = new \Utilities\PayPal\PayPalRestApi(
                        Context::getContextByKey("PAYPAL_CLIENT_ID"),
                        Context::getContextByKey("PAYPAL_CLIENT_SECRET"),
                        Context::getContextByKey("PAYPAL_MODE") ?: "sandbox"
                    );

                    // Crear URL de retorno CON PARÁMETROS
                    $returnUrl = Context::getContextByKey("BASE_URL") .
                        "index.php?page=Checkout_Capture&orderId=" . urlencode($paypalOrder['id']);

                    // Crear la orden en PayPal
                    $paypalOrder = $PayPalRestApi->createOrder(
                        $subTotal,
                        'USD',
                        [
                            'return_url' => $returnUrl

                        ]
                    );

                    error_log("PayPal createOrder response: " . json_encode($paypalOrder));

                    if (isset($paypalOrder['id']) && isset($paypalOrder['status']) && $paypalOrder['status'] === 'CREATED') {

                        $orderData = [
                            'order_id' => $paypalOrder['id'],
                            'user_id' => Security::isLogged() ? Security::getUserId() : null,
                            'total_amount' => $subTotal,
                            'cart_items' => $cartSummary,
                            'created_at' => date('Y-m-d H:i:s'),
                            'status' => 'pending'
                        ];


                        $this->saveOrderToFile($paypalOrder['id'], $orderData);


                        setcookie('pending_order_id', $paypalOrder['id'], time() + 3600, '/');

                        // Buscar la URL de aprobación
                        $approvalUrl = '';
                        foreach ($paypalOrder['links'] as $link) {
                            if ($link['rel'] === 'approve') {
                                $approvalUrl = $link['href'];
                                break;
                            }
                        }

                        if (!empty($approvalUrl)) {
                            // Redirigir a PayPal
                            header('Location: ' . $approvalUrl);
                            exit;
                        } else {
                            Site::redirectTo('index.php?page=Checkout_Checkout&error=no_approval_url');
                            die();
                        }
                    } else {
                        error_log("ERROR: Invalid PayPal response");
                        Site::redirectTo('index.php?page=Checkout_Checkout&error=paypal_create_failed');
                        die();
                    }
                } catch (\Exception $e) {
                    error_log("EXCEPTION in PayPal createOrder: " . $e->getMessage());
                    Site::redirectTo('index.php?page=Checkout_Checkout&error=paypal_exception');
                    die();
                }
            }
        }

        // Calcular subtotales y total para mostrar en la vista
        $subTotal = 0;
        foreach ($items as &$i) {
            $i['itemSubtotal'] = $i['crrprc'] * $i['crrctd'];
            $subTotal += $i['itemSubtotal'];
        }
        $total = $subTotal;

        // Preparar datos para la vista
        $viewData = [
            'items'    => $items,
            'subTotal' => $subTotal,
            'total'    => $total
        ];

        Renderer::render("paypal/checkout", $viewData);
    }

    private function saveOrderToFile($orderId, $data)
    {
        $filename = 'temp_orders/' . $orderId . '.json';
        if (!is_dir('temp_orders')) {
            mkdir('temp_orders', 0777, true);
        }
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
    }
}
