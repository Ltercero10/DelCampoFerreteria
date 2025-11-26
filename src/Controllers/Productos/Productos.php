<?php

namespace Controllers\Productos;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Productos\Productos as DaoProductos;

class Productos extends PublicController
{
    private $viewData = [];
    private $partialName = "";
    private $categoriaId = "";
    private $orderBy = "nombre";
    private $orderDescending = false;
    private $pageNumber = 1;
    private $itemsPerPage = 10;

    public function run(): void
    {
        $this->getParams();

        $tmpProductos = DaoProductos::getProductos(
            $this->partialName,
            $this->categoriaId,
            $this->orderBy,
            $this->orderDescending,
            $this->pageNumber - 1,
            $this->itemsPerPage
        );

        $this->viewData["categorias"] = DaoProductos::getCategorias();

        $this->viewData["categoriaId"] = $this->categoriaId;
        $this->viewData["productos"] = $tmpProductos["productos"];
        $this->viewData["total"] = $tmpProductos["total"];
        $this->viewData["pages"] = $tmpProductos["pages"] ?? 1;
        $this->viewData["pageNum"] = $this->pageNumber;
        $this->viewData["itemsPerPage"] = $this->itemsPerPage;


        Renderer::render("productos/lista", $this->viewData);
    }

    private function getParams(): void
    {
        $this->partialName = $_GET["partialName"] ?? "";
        $this->categoriaId = $_GET["categoriaId"] ?? "";
        $this->orderBy = $_GET["orderBy"] ?? "nombre";
        $this->orderDescending = isset($_GET["orderDescending"])
            ? boolval($_GET["orderDescending"])
            : false;
        $this->pageNumber = isset($_GET["pageNum"])
            ? max(1, intval($_GET["pageNum"]))
            : 1;
    }
}
