<?php

namespace Controllers\Productos; // Ajustado a la estructura /controllers/Productos

use Controllers\PublicController;
use Views\Renderer;
use Dao\Productos\Productos as DaoProductos; // Usamos el DAO de Productos existente
use Utilities\Site;

class Producto extends PublicController
{
    private $viewData = [];
    private $mode = "DSP";
    private $modeDsc = [
        "DSP" => "Ver Producto: %s",
        "INS" => "Crear Nuevo Producto",
        "UPD" => "Actualizar Producto: %s",
        "DEL" => "Eliminar Producto: %s"
    ];

    private $isReadOnly = "readonly";
    private $hasErrors = false;
    private $errors = [];
    private $crf_token = "";


    private $id = 0;
    private $nombre = "";
    private $descripcion = "";
    private $precio = 0.00;
    private $imagen = "";
    private $stock = 0;
    private $estado = "ACT";
    private $categoria_id = 0;



    private function throwError($message, $scope = "global")
    {
        $this->hasErrors = true;
        error_log($message);
        if (!isset($this->errors[$scope])) {
            $this->errors[$scope] = [];
        }
        $this->errors[$scope][] = $message;
    }

    private function validateCsfrToken(): bool
    {
        if ($this->crf_token !== ($_SESSION["crf_token"] ?? "")) {
            $this->throwError("Error de aplicación, Token CSRF Inválido");
            return false;
        }
        return true;
    }

    private function csfrToken()
    {
        $this->crf_token = md5(uniqid(rand(), true));
        $_SESSION["crf_token"] = $this->crf_token;
    }



    private function cargarDatos()
    {
        $this->id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
        $this->mode = isset($_GET["mode"]) ? $_GET["mode"] : "DSP";

        if ($this->id > 0) {
            $producto = DaoProductos::obtenerProductoPorId($this->id);

            if ($producto) {

                $this->id = $producto["id"];
                $this->nombre = $producto["nombre"];
                $this->descripcion = $producto["descripcion"];
                $this->precio = floatval($producto["precio"]);
                $this->imagen = $producto["imagen"];
                $this->stock = intval($producto["stock"]);
                $this->estado = $producto["estado"];
                $this->categoria_id = intval($producto["categoria_id"]);
            } else if ($this->mode !== "INS") {

                Site::redirectToWithMsg(
                    "index.php?page=Productos_Productos",
                    "Producto no encontrado."
                );
            }
        }
    }

    private function getPostData()
    {
        // 1. Cargar datos del POST
        $tmp_nombre = isset($_POST["nombre"]) ? strval($_POST["nombre"]) : "";
        $tmp_descripcion = isset($_POST["descripcion"]) ? strval($_POST["descripcion"]) : "";
        $tmp_precio = isset($_POST["precio"]) ? floatval($_POST["precio"]) : 0.00;
        $tmp_imagen = isset($_POST["imagen"]) ? strval($_POST["imagen"]) : ""; // Si se maneja vía formulario
        $tmp_stock = isset($_POST["stock"]) ? intval($_POST["stock"]) : 0;
        $tmp_estado = isset($_POST["estado"]) ? strval($_POST["estado"]) : "ACT";
        $tmp_categoria_id = isset($_POST["categoria_id"]) ? intval($_POST["categoria_id"]) : 0;

        $tmp_mode = isset($_POST["mode"]) ? $_POST["mode"] : "DSP";
        $this->crf_token = isset($_POST["crf_token"]) ? $_POST["crf_token"] : "";

        // 2. Validaciones básicas
        if ($this->mode !== $tmp_mode) {
            $this->throwError("Modo de formulario inválido");
        }
        if (!$this->validateCsfrToken()) {
            // El error ya se lanza dentro de validateCsfrToken()
        }

        if ($tmp_mode !== "DEL") {
            if (empty($tmp_nombre) || $tmp_precio <= 0) {
                $this->throwError("Nombre y Precio son obligatorios y deben ser válidos.");
            }
            if ($tmp_categoria_id <= 0) {
                $this->throwError("Debe seleccionar una Categoría.");
            }
        }

        // 3. Asignar datos a propiedades
        $this->nombre = $tmp_nombre;
        $this->descripcion = $tmp_descripcion;
        $this->precio = $tmp_precio;
        $this->imagen = $tmp_imagen;
        $this->stock = $tmp_stock;
        $this->estado = $tmp_estado;
        $this->categoria_id = $tmp_categoria_id;
        $this->mode = $tmp_mode;

        // Nota: Para INS/UPD/DEL, el $this->id se carga desde el GET/POST inicial antes de llamar a getPostData().
        if ($this->mode !== "INS" && $this->id <= 0) {
            $this->throwError("ID de producto inválido para la acción.");
        }
    }

    // === Lógica de Acción ===

    private function processAction()
    {
        // Datos comunes para el DAO (para INS/UPD)
        $productoData = [
            "id" => $this->id,
            "nombre" => $this->nombre,
            "descripcion" => $this->descripcion,
            "precio" => $this->precio,
            "stock" => $this->stock,
            "categoria_id" => $this->categoria_id,
            "estado" => $this->estado,
            "imagen" => $this->imagen // Campo de imagen (si se maneja en el formulario)
        ];

        switch ($this->mode) {
            case "INS":
                $inserted = DaoProductos::insertProducto($productoData);
                if ($inserted) {
                    Site::redirectToWithMsg(
                        "index.php?page=Productos_Productos",
                        "Producto '{$this->nombre}' Agregado Exitosamente"
                    );
                } else {
                    $this->throwError("Error al agregar el registro en la base de datos.");
                }
                break;
            case "UPD":
                $updated = DaoProductos::updateProducto($productoData); // Tu DAO usa el array completo
                if ($updated) {
                    Site::redirectToWithMsg(
                        "index.php?page=Productos_Productos",
                        "Producto '{$this->nombre}' Actualizado Exitosamente"
                    );
                } else {
                    $this->throwError("Error al actualizar el registro en la base de datos.");
                }
                break;
            case "DEL":
                // Aquí podrías usar deleteProducto (borrado físico) o updateProducto (cambio de estado a DEL/INA)
                // Usaré tu método de borrado físico que definiste:
                $deleted = DaoProductos::deleteProducto($this->id);
                if ($deleted) {
                    Site::redirectToWithMsg(
                        "index.php?page=Productos_Productos",
                        "Producto con ID {$this->id} Eliminado Exitosamente"
                    );
                } else {
                    $this->throwError("Error al eliminar el registro en la base de datos. Podría no existir.");
                }
                break;
        }
    }

    // === Preparación de la Vista ===

    private function prepareViewData()
    {

        // Obtener categorías para el dropdown en INS/UPD
        $this->viewData["categorias"] = DaoProductos::getCategorias();

        // Mapeo de datos para la vista
        $this->viewData["mode"] = $this->mode;
        $this->viewData["modeDesc"] = sprintf(
            $this->modeDsc[$this->mode],
            $this->nombre ?: $this->id
        );
        $this->viewData["id"] = $this->id;
        $this->viewData["nombre"] = $this->nombre;
        $this->viewData["descripcion"] = $this->descripcion;
        $this->viewData["precio"] = $this->precio;
        $this->viewData["imagen"] = $this->imagen;
        $this->viewData["stock"] = $this->stock;
        $this->viewData["estado"] = $this->estado;
        $this->viewData["categoria_id"] = $this->categoria_id;

        // Lógica de solo lectura/editable
        if ($this->mode === "INS" || $this->mode === "UPD") {
            $this->isReadOnly = ""; // Vacío permite edición
        }
        $this->viewData["isReadOnly"] = $this->isReadOnly;
        $this->viewData["showAction"] = $this->mode !== "DSP";

        // Manejo de errores y CSRF
        $this->csfrToken();
        $this->viewData["crf_token"] = $this->crf_token;
        $this->viewData["hasErrors"] = $this->hasErrors;
        $this->viewData["errors"] = $this->errors;

        $this->viewData["isReadOnly"] = $this->isReadOnly;

        // ESTA LÍNEA DEBE ESTAR:
        $this->viewData["showAction"] = $this->mode !== "DSP";

        // ✅ NUEVA LÓGICA: Preparar el texto y el color del botón
        $this->viewData["actionBtnText"] = "";
        $this->viewData["actionBtnClass"] = "";

        switch ($this->mode) {
            case "INS":
                $this->viewData["actionBtnText"] = "➕ Crear Producto";
                $this->viewData["actionBtnClass"] = "btn-success";
                break;
            case "UPD":
                $this->viewData["actionBtnText"] = "💾 Guardar Cambios";
                $this->viewData["actionBtnClass"] = "btn-warning";
                break;
            case "DEL":
                $this->viewData["actionBtnText"] = "🗑️ Confirmar Eliminación";
                $this->viewData["actionBtnClass"] = "btn-danger";
                break;
        }
        $this->viewData["isInsMode"] = $this->mode === "INS";
        $this->viewData["isUpdMode"] = $this->mode === "UPD";
        $this->viewData["isDelMode"] = $this->mode === "DEL";
        $this->viewData["isDspMode"] = $this->mode === "DSP";
    }



    public function run(): void
    {

        $this->cargarDatos();


        if ($this->isPostBack()) {
            $this->getPostData();
            if (!$this->hasErrors) {
                $this->processAction();
            }
        }


        $this->prepareViewData();
        Renderer::render("productos/productoform", $this->viewData);
    }
}
