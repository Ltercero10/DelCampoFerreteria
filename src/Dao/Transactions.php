<?php

namespace Dao;

class Transactions extends Table
{

    public static function save(
        int $usuarioId,
        string $orderId,
        float $monto,
        string $estado = 'completado',
        array $productos = []
    ): bool {
        $sql = "INSERT INTO transacciones 
                (usuario_id, order_id, monto, estado, productos) 
                VALUES (:usuario_id, :order_id, :monto, :estado, :productos)";

        $params = [
            "usuario_id" => $usuarioId,
            "order_id" => $orderId,
            "monto" => $monto,
            "estado" => $estado,
            "productos" => json_encode($productos)
        ];

        return self::executeNonQuery($sql, $params) > 0;
    }

    public static function getByUser(int $usuarioId): array
    {
        $sql = "SELECT * FROM transacciones 
                WHERE usuario_id = :usuario_id 
                ORDER BY fecha DESC";

        return self::obtenerRegistros($sql, ["usuario_id" => $usuarioId]);
    }
}
