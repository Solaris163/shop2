<?php
namespace app\model;

class Users extends DbModel
{
    public $id;
    public $login;
    public $pass;
    public $hash;

    public function __construct($login = null, $pass = null, $hash = null)
    {
        //parent::__construct();
        $this->login = $login;
        $this->pass = $pass;
        $this->hash = $hash;
    }

    public static function getTableName()
    {
        return "users";
    }

    public function getProperties() {
        return ['login'=>$this->login, 'pass'=>$this->pass, 'hash'=>$this->hash];
    }

    public static function is_auth() { //функция проверяет есть ли пользователь в сессии если есть возвращает true,
        //если нет, проверяет есть ли хеш в cookie, если есть то записывает пользователя в сессию

        if(isset($_SESSION['login'])) { //проверяем, есть ли в сессии логин пользователя
            return true;
        } elseif(isset($_COOKIE['hash'])) { //проверяем, есть ли в COOKIE хеш
            $hash = $_COOKIE['hash'];
            $user = static::getOneWhere('hash', $hash); //запрашиваем объект пользователя из базы по хешу
            if($user) { //проверяем создался ли объект пользователя из базы (если не создался, $user будет равно false)
                $_SESSION['login'] = $user->login; //если объект создался, записываем в сессию логин пользователя
                return true;
            } else return false;
        }else return false;
    }

    public static function getLogin() {
        return $_SESSION['login'];
    }

    public static function is_admin() {
        return ($_SESSION['login'] == 'admin');
    }

}