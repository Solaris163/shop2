<?php
namespace app\model;

use app\engine\Db as Db;

class Cart extends DbModel
{
    public $id;
    public $session;
    public $user;
    public $id_product;
    public $quantity;

    public function __construct($session = null, $user = null, $id_product = null, $quantity = null)
    {
        $this->session = $session;
        $this->user = $user;
        $this->id_product = $id_product;
        $this->quantity = $quantity;
    }

    public static function getCart() { //пока корзина определяется только по сессии
        $session = session_id();
        $sql = "SELECT * FROM products, cart WHERE cart.id_product = products.id AND cart.session = '{$session}' AND status = 1";
        return Db::getInstance()->queryAll($sql);
    }

    public static function getTableName()
    {
        return "cart";
    }

    public function getProperties() {
        return ['session'=>$this->session, 'user'=>$this->user, 'id_product'=>$this->id_product,
            'quantity'=>$this->quantity];
    }
}