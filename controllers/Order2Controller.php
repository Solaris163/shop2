<?php

namespace app\controllers;

use app\model\DbModel;
use app\model\Orders;
use app\model\Users;

class Order2Controller extends Controller
{
    public function actionIndex() {
        $admin = Users::is_admin();
        $orders = $this->getOrders();
        echo $this->render("orders2", ['orders' => $orders, 'admin' => $admin]);
    }

    public function actionSet_paid($params) { //метод устанавливает статус "paid" для ордера с id, взятым из URL
        Orders::changeStatus('id', (int)$params['id'], 'paid');
        header("Location: /order2/");
    }

    public function actionSet_send($params) { //метод устанавливает статус "paid" для ордера с id, взятым из URL
        Orders::changeStatus('id', (int)$params['id'], 'send');
        header("Location: /order2/");
    }

    public function actionSet_received($params) { //метод устанавливает статус "paid" для ордера с id, взятым из URL
        Orders::changeStatus('id', (int)$params['id'], 'received');
        header("Location: /order2/");
    }

    public function getOrders() { //функция получилась компактная, но пока с множественными запросами в базу данных
        $orders = Orders::getAll(); //массив всех ордеров
        foreach ($orders as $key => $order) { //переберем все заказы в массиве $orders
            //(потом это будет происходить непосредственно на сервере базы данных)
            //Создадим $products - это будет массив с товарами одного заказа и получим данные из таблицы sold_products
            $products = DbModel::getFromTableWhere('sold_products', 'order_id', $order['id']);
            $orders[$key]['products'] = $products; //вставляем в заказ массив с товарами
        }
        return $orders;
    }
}