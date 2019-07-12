<?php

namespace app\controllers;

use app\engine\Db;
use app\model\Cart;
use app\model\Orders;
use app\model\Products;
use app\model\Users;

class CheckoutController extends Controller
{
    public function actionIndex() {
        echo $this->render("checkout");
    }

    public function actionMake_order($params) {
        $auth = (Users::is_auth()) ? 1 : 0; //$auth равен 1 или 0 в зависимости от того, авторизован ли пользователь
        $user = Users::getLogin(); //логин пользователя
        $session = Session_id();
        $name = $params['name']; //имя введенное пользователем при оформлении заказа
        $phone = $params['phone']; //телефон введенный пользователем при оформлении заказа
        $products = $this->getProducts($session);//товары из корзины поьзователя (пока корзина определяется по сессии)
        $status = 'new';
        $order = new Orders($auth, $user, $session, $name, $phone, $products, $status);
        $order->save();
        $order->addProductsForOrder($order->id, $products);//запишем продукты из ордера в таблицу sold_products
        Cart::changeStatus('session', $session, 0); //меняем статус товаров в корзине с 1 на 0
        header("Location: /checkout/checkout_success/?id={$order->id}");
    }

    //метод getProducts возвращает товары из корзины пользователя
    public function getProducts($session) { //не стал выносить этот метод в DbModel, так как он специфический
        $sql = "SELECT id_product,quantity,name,price FROM products,cart WHERE cart.id_product = products.id AND session = :session AND status = 1";
        return Db::getInstance()->queryAll($sql, [':session'=>$session]);
    }

    public function actionCheckout_success($params) {
        echo $this->render("checkout_success", ['order_id' => $params['id']]);
    }

}