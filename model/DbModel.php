<?php
namespace app\model;

use app\engine\Db as Db;

abstract class DbModel extends Models
{
    public static function getOne($id) { //возвращает объект по id
        $tableName = static::getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        return Db::getInstance()->queryObject($sql, [':id'=>$id], static::class);
    }

    public static function getAll() {
        $tableName = static::getTableName();
        $sql = "SELECT * FROM {$tableName}";
        return Db::getInstance()->queryAll($sql);
    }

    public function save() { //метод проверяет есть ли у объекта id, и если нет, то вызывает insert(), иначе update()
        if(is_null($this->id)) {
            $this->insert();
        } else {
            $this->update();
        }
    }

    public function insert() { //метод вставляет данные объекта в таблицу, соответствующую этому объекту
        $tableName = $this->getTableName();
        $propertiesArr = $this->getProperties();//массив со свойствами класса, которые необходимо передать в базу данных.

        $fields = implode(', ', array_keys($propertiesArr));//поля таблицы, куда нужно внести данные
        $valuesArr = $this->getValues($propertiesArr);//функция прибавляет двоеточие к названиям ключей массива.
        $values = implode(', ', $valuesArr);

        $sql = "INSERT INTO {$tableName} ({$fields}) values ({$values})";

        $bindArr = [];//массив вида [':id'=>$id], для биндирования значений в sql запросе (если я правильноо выразился).
        foreach (array_keys($propertiesArr) as $key) {
            $bindArr[":{$key}"] = $propertiesArr[$key];
        }


        Db::getInstance()->execute($sql, $bindArr);
        //echo "Добавление в таблицу {$tableName} произведено<br>";

        $this->id = Db::getInstance()->getLastId();//после добавления объекта в базу, у него сразу заполняется поле id
    }

    public function update() { //метод обновляет данные в таблице
        $tableName = $this->getTableName();
        $propertiesArr = $this->getProperties();//массив со свойствами класса, которые необходимо передать в базу данных.

        $params = [];
        $setArr = [];

        foreach ($propertiesArr as $key => $value) {
            $params[":{$key}"] = $value;
            $setArr[] = "{$key} = :{$key}";//массив с данными для вставки в sql-запрос после слова SET
        }

        $params[":id"] = $this->id;
        $setStr = implode(', ', $setArr);

        $sql = "UPDATE {$tableName} SET {$setStr} WHERE id = :id";

        Db::getInstance()->execute($sql, $params);
    }

    protected function getValues($propertiesArr) {
        $arrKeys = []; //массив ключей из массива $propertiesArr, но к названию ключа прибавляется двоеточие
        foreach (array_keys($propertiesArr) as $key) {
            array_push($arrKeys, ":{$key}");
        }
        return $arrKeys;
    }

    public function delete() {
        $tableName = $this->getTableName();
        $sql = "DELETE FROM {$tableName} WHERE id = :id";
        Db::getInstance()->execute($sql, [':id' => $this->id]);
    }

    public static function getCountWhere($field, $value) { //возвращает число строк в таблице, подходящих условию
        $tableName = static::getTableName();
        $sql = "SELECT count(*) as count FROM {$tableName} WHERE {$field} = :value";
        $result = Db::getInstance()->queryOne($sql, ['value' => $value]);
        $count = $result['count'];
        return $count;
    }
    //возвращает число строк в таблице, подходящих условию плюс проверяет равен ли статус строки 1
    public static function getCountWhere_WithStatus($field, $value) {
        $tableName = static::getTableName();
        $sql = "SELECT count(*) as count FROM {$tableName} WHERE {$field} = :value AND status = 1";
        $result = Db::getInstance()->queryOne($sql, ['value' => $value]);
        $count = $result['count'];
        return $count;
    }

    public static function getOneWhere($field, $value) { //возвращает объект, подходящий условию
        $tableName = static::getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE {$field} = :value";
        return Db::getInstance()->queryObject($sql, [':value'=>$value], static::class);
    }

    public static function getFromTableWhere($table, $field, $value) {
        $sql = "SELECT * FROM {$table} WHERE {$field} = :value";
        return Db::getInstance()->queryAll($sql, [':value'=>$value]);
    }

    //метод меняет в таблице в строке с $field = $value статус на $new_status
    public static function changeStatus($field, $value, $new_status) {
        $tableName = static::getTableName();
        $sql = "UPDATE {$tableName} SET status = :new_status WHERE {$field} = :value";
        Db::getInstance()->execute($sql, [':new_status' => $new_status, ':value' => $value]);
}

    abstract public static function getTableName();//метод возвращает название таблицы, соответствующей этому объекту
    abstract public function getProperties(); //метод подготовки массива со свойствами будет реализован в каждом классе
}