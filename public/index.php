<?php

session_start();
include "../engine/Autoload.php";
include "../config/config.php";
use app\model\Products;
use app\model\Users;
use app\model\Orders;
use app\model\Cart;
use app\engine\Render;
use app\engine\TwigRender;
use app\engine\Request;

/** @var Products $product */

require_once "../vendor/autoload.php";


spl_autoload_register([new Autoload(), 'loadClass']);

$request = new Request();//создаем объект $request, который прочитает команды из URL

$controllerName = $request->getControllerName()?: 'product';
$actionName = $request->getActionName();//переменную $params надо потом ввести чтобы убрать все $_GET
$params = $request->getParams(); //id и прочие параметры для работы методов action

$controllerClass = "app\\controllers\\" . ucfirst($controllerName) . "Controller";


if (class_exists($controllerClass)) {
    $controller = new $controllerClass(new TwigRender());
    $controller->runAction($actionName, $params); //передаем имя метода и другие параметры из URL
}

