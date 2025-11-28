<?php

namespace Dao\Productos;

use Dao\Table;

class Productos extends Table
{
    public static function getProductos(
        string $partialName = "",
        string $categoriaId = "",
        string $orderBy = "nombre",
        bool $orderDescending = false,
        int $page = 0,
        int $itemsPerPage = 10
    ) {
        $sqlstr = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM productos p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.estado = 'ACT'";

        $conditions = [];
        $params = [];

        if (!empty($partialName)) {
            $conditions[] = "p.nombre LIKE :partialName";
            $params["partialName"] = "%" . $partialName . "%";
        }

        if (!empty($categoriaId)) {
            $conditions[] = "p.categoria_id = :categoriaId";
            $params["categoriaId"] = $categoriaId;
        }

        if (count($conditions) > 0) {
            $sqlstr .= " AND " . implode(" AND ", $conditions);
        }


        $allowedOrderBy = ["nombre", "precio", "stock", "creado_en"];
        if (in_array($orderBy, $allowedOrderBy)) {
            $sqlstr .= " ORDER BY p." . $orderBy;
            $sqlstr .= $orderDescending ? " DESC" : " ASC";
        }


        $offset = $page * $itemsPerPage;
        $sqlstr .= " LIMIT " . $offset . ", " . $itemsPerPage;


        $productos = self::obtenerRegistros($sqlstr, $params);

        $sqlCount = "SELECT COUNT(*) as total FROM productos p WHERE p.estado = 'ACT'";
        if (count($conditions) > 0) {
            $sqlCount .= " AND " . implode(" AND ", $conditions);
        }

        $total = self::obtenerUnRegistro($sqlCount, $params)["total"];

        return [
            "productos" => $productos,
            "total" => $total,
            "pages" => ceil($total / $itemsPerPage)
        ];
    }

    public static function getCategorias()
    {
        $sqlstr = "SELECT id, nombre FROM categorias WHERE estado = 'ACT' ORDER BY nombre";
        return self::obtenerRegistros($sqlstr, []);
    }
    public static function getProductoById(int $id)
    {
        $sqlstr = "SELECT p.*, c.nombre as categoria_nombre 
               FROM productos p
               LEFT JOIN categorias c ON p.categoria_id = c.id
               WHERE p.id = :id LIMIT 1";

        return self::obtenerUnRegistro($sqlstr, ["id" => $id]);
    }
    public static function actualizarProductoImagen(int $id, string $imagenPath)
    {
        $sql = "UPDATE productos SET imagen = :imagen WHERE id = :id";
        // Se ejecuta como si fuera un SELECT, aunque es un UPDATE
        return self::obtenerUnRegistro($sql, ["imagen" => $imagenPath, "id" => $id]);
    }

    public static function updateProducto($producto)
    {
        $sql = "UPDATE productos 
            SET nombre = :nombre,
                descripcion = :descripcion,
                precio = :precio,
                stock = :stock,
                categoria_id = :categoria_id";

        // Solo agrega la imagen si viene en el array
        if (isset($producto["imagen"])) {
            $sql .= ", imagen = :imagen";
        }

        $sql .= " WHERE id = :id";

        return self::executeNonQuery($sql, $producto);
    }

    public static function insertProducto($producto)
    {
        $sql = "INSERT INTO productos 
            (nombre, descripcion, precio, stock, categoria_id, imagen)
            VALUES 
            (:nombre, :descripcion, :precio, :stock, :categoria_id, :imagen)";

        return self::executeNonQuery($sql, $producto);
    }
}
