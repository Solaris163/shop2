<?php
namespace app\engine;

use app\traits\Tsingletone;

class Db
{
    use Tsingletone;

    private $config = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'shop',
        'charset' => 'utf8',
    ];

    private $connection = null;



    private function getConnection() {
        if (is_null($this->connection)) {
            $this->connection = new \PDO($this->prepareDSNstr(),
                $this->config['login'],
                $this->config['password']
                );
            $this->connection->setAttribute(
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC);
        }
        return $this->connection;
    }

    private function prepareDSNstr() {
        return sprintf("%s:host=%s;dbname=%s;charset=%s",
            $this->config['driver'],
            $this->config['host'],
            $this->config['database'],
            $this->config['charset']
            );
    }

    private function query($sql, $params) {
        $pdoStatement = $this->getConnection()->prepare($sql);
        $pdoStatement->execute($params);
        return $pdoStatement;
    }

    public function execute($sql, $params) {
        $this->query($sql,$params);
        return true;
    }

    public function queryOne($sql, $param = []) {
        return $this->queryAll($sql, $param)[0];
    }

    public function queryAll($sql, $param = []) {
        return $this->query($sql, $param)->fetchAll();
    }

    public function queryObject($sql, $params, $class) {
        $pdoStatement = $this->query($sql, $params);
        $pdoStatement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class);
        return $pdoStatement->fetch();
    }

    public function getLastId() {
        return $this->connection->lastInsertId();
    }
}