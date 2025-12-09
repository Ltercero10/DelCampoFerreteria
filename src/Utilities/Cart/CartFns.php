<?php

namespace Utilities\Cart;

use Unicah\Oop\Utilitarios\StandardLogger;

use Dao\Cart\Cart;

class CartFns
{

    public static function getAuthTimeDelta()
    {
        return 21600; // 6 * 60 * 60; // horas * minutos * segundo
        // No puede ser mayor a 34 días
    }

    public static function getUnAuthTimeDelta()
    {
        return 600; // 10 * 60; //h , m, s
        // No puede ser mayor a 34 días
    }

    public static function getAnnonCartCode()
    {
        if (isset($_SESSION["annonCartCode"])) {
            return $_SESSION["annonCartCode"];
        };
        $_SESSION["annonCartCode"] = substr(md5("cart2025" . time() . random_int(10000, 99999)), 0, 128);
        return $_SESSION["annonCartCode"];
    }
    public static function getAuthCartItemCount(int $usercod): int
    {
        return Cart::getCantidadCarritoUsuario($usercod);
    }


    public static function getAnonCartItemCount(string $anonCod): int
    {
        return Cart::getCantidadCarritoAnonimo($anonCod);
    }
}
