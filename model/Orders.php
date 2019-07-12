<?php
namespace app\model;

use app\engine\Db as Db;

class Orders extends DbModel
{
    public $id;
    public $auth;
    public $user;
    public $session;
    public $name;
    public $phone;
    public $products;
    public $status;

    public function __construct($auth=null, $user=null, $session=null, $name=null, $phone=null, $products=null, $status=null)
    {
        $this->auth = $auth;
        $this->user = $user;
        $this->session = $session;
        $this->name = $name;
        $this->phone = $phone;
        $this->products = $products;
        $this->status = $status;
    }

    public static function getNamesById($id_products_arr) { //функция отдает названия товаров по их id
        $id_products_str = implode (",", $id_products_arr);//переводим массив id в строку
        $sql = "SELECT `id`,`name` FROM `products` where id in ({$id_products_str})";
        return Db::getInstance()->queryAll($sql);
    }

    //function addProductsForOrder добавляет товары из заказа в таблицу sold_products, число строк равно числу товаров
    public static function addProductsForOrder($order_id, $products) {
        $fields = 'order_id, product_id, product_name, price, quantity'; //поля в таблице sold_products
        $valuesArr = []; //массив товаров, добавляемых в таблицу sold_products
        foreach ($products as $product) {
            //$arr - массив с данными для одной строки добавляемой в таблицу, соответствующий одному добавляемому товару
            $arr = [$order_id, $product['id_product'], "'{$product['name']}'", $product['price'], $product['quantity']];
            $row = '(' . implode(', ', $arr) . ')';
            $valuesArr[] = $row;
        }
        $values = implode(', ', $valuesArr);//
        $sql = "INSERT INTO sold_products ({$fields}) VALUES {$values}";
        Db::getInstance()->execute($sql, []);
    }

    public static function getTableName()
    {
        return "orders";
    }

    public function getProperties() {
        return ['auth'=>$this->auth, 'user'=>$this->user, 'session'=>$this->session,
            'name'=>$this->name, 'phone'=>$this->phone, 'products'=>$this->products,
            'status'=>$this->status];
    }
}