<?php

namespace app\engine;

//Класс обрабатывает все запросы из URL, возвращает имя controller, имя action и массив других данных
//Используется вместо глобального массива $_GET
class Request
{
    protected $requestString;
    protected $method;
    protected $controllerName;
    protected $actionName;
    protected $params;

    public function __construct()
    {
        $this->requestString = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->parseRequest();
    }

    private function parseRequest() {
        $url =  explode('/', $this->requestString);
        $this->controllerName = $url[1];
        $this->actionName = $url[2];
        $this->params = $_REQUEST;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getActionName()
    {
        return $this->actionName;
    }

    public function getParams()
    {
        return $this->params;
    }



}