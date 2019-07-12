<?php

namespace app\controllers;


use app\model\Products;

class ProductController extends Controller
{
    public function actionIndex() {
        $catalog = Products::getAll();
        echo $this->render("catalog", ['catalog' => $catalog]);
    }

    public function actionCard() {
        $id = $_GET['id'];
        $product = Products::getOne($id);
        $product = (array) $product;
        echo $this->render("card", ['product' => $product]);
    }
}