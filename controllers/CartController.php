<?php

namespace app\controllers;

use app\model\Cart;
use app\model\Users;

class CartController extends Controller
{
    public function actionIndex() { //метод рендерит страницу корзины
        $cart = Cart::getCart();
        echo $this->render("cart", ['cart' => $cart]);
    }

    public function actionAddToCart($params) { //этот action не рендерит страницу, а только добавляет товар в корзину
        $user = Users::getLogin();
        $product = new Cart(session_id(), $user, $params['id'], 1);//создаем объект для добавления
        $product->save();
        header("Location: /");//после добавления, перенаправляем на главную страницу
    }

    public function actionAddToCartAsinc($params) { //для асинронного запроса
        $user = Users::getLogin();
        $product = new Cart(session_id(), $user, $params['id'], 1);//создаем объект для добавления
        $product->save();
        echo json_encode(['response' => 1]);//возврат сообщения об успешном добавлении для обновления страницы
        //header("Location: /");//после добавления, перенаправляем на главную страницу
    }

    public function actionDeleteFromCart($params) { //этот action тоже не рендерит страницу, а удаляет товар
        $product = new Cart(null, null, $params['id'], 1);//создаем объект для удаления
        $product->id = $params['id'];
        $product->delete();
        header("Location: /cart");//обратно на страницу корзины где по умолчанию сработает actionIndex()
    }
}