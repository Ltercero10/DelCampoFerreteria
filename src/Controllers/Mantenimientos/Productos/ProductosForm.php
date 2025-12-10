<?php

namespace Controllers\Mantenimientos\Productos;

use Controllers\PrivateController;
use Dao\Mantenimientos\Productos\Productos as ProductosDao;
use Controllers\PublicController;
use Views\Renderer;
use Utilities\Site;

class ProductosForm extends PrivateController
{
    private $viewData = [];
    private $mode = "DSP";
    private $modeDsc = [
        "DSP" => "Ver Producto",
        "INS" => "Agregar Producto",
        "UPD" => "Editar Producto",
        "DEL" => "Eliminar Producto"
    ];


    private $isReadOnly = "readonly";
    private $hasErrors = false;
    private $errors = [];
    private $crf_token = "";

    private $productId = 0;
    private $productName = "";
    private $productDescription = "";
    private $productPrice = 0;
    private $productImgUrl = "";
    private $productStock = 0;
    private $productStatus = "";

    private function throwError($message, $scope = "global")
    {
        $this->hasErrors = true;
        error_log($message);
        if (!isset($this->errors[$scope])) {
            $this->errors[$scope] = [];
        }
        $this->errors[$scope][] = $message;
    }

    private function cargarDatos()
    {
        $this->productId = isset($_GET["productId"]) ? intval($_GET["productId"]) : 0;
        $this->mode = isset($_GET["mode"]) ? $_GET["mode"] : "DSP";

        if ($this->productId > 0) {
            $product = ProductosDao::getByPrimaryKey($this->productId);
            if ($product) {
                $this->productId = $product["productId"];
                $this->productName = $product["productName"];
                $this->productDescription = $product["productDescription"];
                $this->productPrice = $product["productPrice"];
                $this->productImgUrl = $product["productImgUrl"];
                $this->productStock = $product["productStock"];
                $this->productStatus = $product["productStatus"];
            }
        }
    }

    private function getPostData()
    {
        $tmp_productName = $_POST["productName"] ?? "";
        $tmp_productDescription = $_POST["productDescription"] ?? "";
        $tmp_productPrice = isset($_POST["productPrice"]) ? floatval($_POST["productPrice"]) : 0;
        $tmp_productImgUrl = $_POST["productImgUrl"] ?? "";
        $tmp_productStock = isset($_POST["productStock"]) ? intval($_POST["productStock"]) : 0;
        $tmp_productStatus = $_POST["productStatus"] ?? "";
        $tmp_mode = $_POST["mode"] ?? "DSP";
        $tmp_crf_token = $_POST["crf_token"] ?? "";

        if ($this->mode !== $tmp_mode) {
            $this->throwError("Modo de formulario inválido");
        }
        if (!$this->validateCsfrToken()) {
            $this->throwError("Error de aplicación, Token CSRF Inválido");
        }

        $this->productName = $tmp_productName;
        $this->productDescription = $tmp_productDescription;
        $this->productPrice = $tmp_productPrice;
        $this->productImgUrl = $tmp_productImgUrl;
        $this->productStock = $tmp_productStock;
        $this->productStatus = $tmp_productStatus;
        $this->mode = $tmp_mode;
    }

    private function validateCsfrToken()
    {
        if ($this->crf_token !== ($_SESSION["crf_token"] ?? "")) {
            $this->throwError("Token CSRF Inválido");
            return false;
        }
        return true;
    }

    private function csfrToken()
    {
        $this->crf_token = md5(uniqid(rand(), true));
        $_SESSION["crf_token"] = $this->crf_token;
    }

    private function processAction()
    {
        switch ($this->mode) {
            case "INS":
                $inserted = ProductosDao::add(
                    $this->productName,
                    $this->productDescription,
                    $this->productPrice,
                    $this->productImgUrl,
                    $this->productStock,
                    $this->productStatus
                );
                if ($inserted) {
                    Site::redirectToWithMsg(
                        "index.php?page=Mantenimientos_Productos_ProductosList",
                        "Producto agregado correctamente"
                    );
                } else {
                    $this->throwError("Error al agregar el producto");
                }
                break;

            case "UPD":
                $updated = ProductosDao::update(
                    $this->productId,
                    $this->productName,
                    $this->productDescription,
                    $this->productPrice,
                    $this->productImgUrl,
                    $this->productStock,
                    $this->productStatus
                );
                if ($updated) {
                    Site::redirectToWithMsg(
                        "index.php?page=Mantenimientos_Productos_ProductosList",
                        "Producto actualizado correctamente"
                    );
                } else {
                    $this->throwError("Error al actualizar el producto");
                }
                break;

            case "DEL":
                $deleted = ProductosDao::delete($this->productId);
                if ($deleted) {
                    Site::redirectToWithMsg(
                        "index.php?page=Mantenimientos_Productos_ProductosList",
                        "Producto eliminado correctamente"
                    );
                } else {
                    $this->throwError("Error al eliminar el producto");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["mode"] = $this->mode;
        $this->viewData["modeDesc"] = sprintf($this->modeDsc[$this->mode], $this->productId);
        $this->viewData["productId"] = $this->productId;
        $this->viewData["productName"] = $this->productName;
        $this->viewData["productDescription"] = $this->productDescription;
        $this->viewData["productPrice"] = $this->productPrice;
        $this->viewData["productImgUrl"] = $this->productImgUrl;
        $this->viewData["productStock"] = $this->productStock;
        $this->viewData["productStatus"] = $this->productStatus;

        if ($this->mode === "INS" || $this->mode === "UPD") {
            $this->isReadOnly = "";
        }

        $this->viewData["isReadOnly"] = $this->isReadOnly;
        $this->viewData["showAction"] = $this->mode !== "DSP";

        $this->csfrToken();
        $this->viewData["crf_token"] = $this->crf_token;

        $this->viewData["hasErrors"] = $this->hasErrors;
        $this->viewData["errors"] = $this->errors;

        $this->viewData["isActionMode"] = $this->mode === "INS" || $this->mode === "UPD" || $this->mode === "DEL";



        if ($this->viewData["mode"] === "DSP") {
            $this->viewData["showAction"] = false;
        }
    }

    public function run(): void
    {

        $this->cargarDatos();


        if ($this->mode !== "DSP") {
            if (!\Utilities\Security::isInRol(\Utilities\Security::getUserId(), 'ADMIN')) {
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=Mantenimientos_Productos_ProductosList",
                    "¡No tienes permiso para realizar esta acción!"
                );
                return;
            }
        }

        if ($this->isPostBack()) {
            $this->getPostData();
            if (!$this->hasErrors) {
                $this->processAction();
            }
        }

        $this->prepareViewData();
        Renderer::render("mantenimientos/productos/productos_form", $this->viewData);
    }
}
