<?php
namespace app\model;

class Products extends DbModel
{
    public $id;
    public $name;
    public $description;
    public $price;

    public function __construct($name = null, $description = null, $price = null)
    {
        //parent::__construct();
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    public static function getTableName()
    {
        return "products";
    }

    public function getProperties() {
        return ['name'=>$this->name, 'description'=>$this->description, 'price'=>$this->price];
    }
}