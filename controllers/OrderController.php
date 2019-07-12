<?php
/**
 * Created by PhpStorm.
 * User: Компьютер
 * Date: 04.04.2019
 * Time: 17:22
 */

namespace app\controllers;

use app\model\Orders;
use app\model\Users;

class OrderController extends Controller //это старый вариант отображения заказов, взял его из курсового php1, он
//работает, но более правильный вариант - новый класс Order2Controller, там используется таблица sold_products, вместо
//того, чтобы записывать все товары в таблицу orders в виде json строки. В новом варианте можно удалить столбец products
//из таблицы orders базы данных. Этот вариант я не буду выкладывать на сервер для портфолио, оставлю только второй
{
    public function actionIndex() {
        $admin = Users::is_admin();
        $orders = $this->getOrders();
        echo $this->render("orders", ['orders' => $orders, 'admin' => $admin]);
    }

    public function getOrders() {
        $orders = Orders::getAll(); //главный массив всех ордеров
        $id_products_arr = [];// массив с id всех товаров, во всех заказах, чтобы по ним сделать запрос на названия товаров

        //так как все товары одного пользователя хранятся в таблице заказов в виде json-строки, надо их превести к массиву
        foreach ($orders as $key => $order) { //переберем все заказы в главном массиве $orders
            //Создадим $products - это будет массив с товарами одного заказа
            $products = json_decode($order["products"], $assoc = TRUE);//преобразуем строку с товарами в ассоциативный массив
            if (!is_array($products)) { //это старый вариант отображения ордеров, он не должен видеть новые заказы
                continue; //прерывем итерацию, если заказ новый, и в поле товаров нет строки json с товарами
            }
            $orders[$key]["products"] = $products; //меняем в массиве $orders соответствующее строковое значение на массив.
            //дальше переберем массив товаров и добавим все id из заказа в массив $id_products_arr
            foreach ($products as $product) {
                if(!in_array($product['id'], $id_products_arr)) { //проверка содержится ли в массиве id данного товара
                    $id_products_arr[] = $product['id']; //если не содержится, то добавляем в массив id данного товара
                }
            }
        }

        //дальше создадим асоциативный массив $product_names_assoc, где ключ это id товара, а значение это название товара

        //получим из базы названия всех товаров по их id
        $product_names = Orders::getNamesById($id_products_arr); //функция находится в классе Orders

        $product_names_assoc = [];//ассоциативный массив где ключ это id товара, а значение это название товара
        foreach ($product_names as $product){
            $product_names_assoc["{$product['id']}"] = $product['name']; //собираем ассоциативный массив
        }

        //дальше нужно вставить в каждый товар, информацию о его названии.
        //для этого перебираем все заказы, а в каждом заказе перебираем каждый товар.
        foreach ($orders as $key1 => $order) {
            if (!is_array($order["products"])) { //это старый вариант отображения ордеров, он не должен видеть новые заказы
                continue; //прерывем итерацию, если заказ новый, и в поле товаров не массив
            }
            foreach ($order["products"] as $key2 => $product) {
                $product["name"] = $product_names_assoc["{$product['id']}"];//добавляем ключ name в каждый продукт заказа
                $order["products"][$key2] = $product; //заменяем прежний массив на тот, к которому добавили ключ name
            }
            $orders[$key1] = $order; //заменяем весь массив одного ордера на новый, в котором добавили названия продуктов
        }

        return $orders;
    }
}