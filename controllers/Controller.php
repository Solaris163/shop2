<?php

namespace app\controllers;

use app\engine\Render;
use app\interfaces\IRenderer;
use app\model\Cart;
use app\model\Users;

class Controller implements IRenderer
{
    private $action;
    private $defaultAction = "index";
    private $layout = 'main';
    private $useLayout = true;
    private $renderer; //переменная в которой хранится метод (twig или наш самодельный)


    public function __construct(IRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function runAction($action = null, $params = null) {//функция выбирает какой action запустить

        $this->action = $action?: $this->defaultAction; //если $action не существует, то $this->action = defaultAction
        $method = "action" . ucfirst($this->action); //название метода = 'action' + $this->action с заглавной буквы

        if(method_exists($this, $method)) { //вызов метода. если он существует, и передача в него аргумента $params.
            $this->$method($params);//
        }else {
            echo '404 метод не существует';
        }
    }

    public function render($template, $params = []) {
        if($this->useLayout) { //проверка нужно ли на странице использовать подслой (layout)
            return $this->renderTemplate(
                "layouts/{$this->layout}",
                [ //массив параметров, передаваемых в layout
                    'content' => $this->renderTemplate($template, $params),//content сам рендерится из шаблона и параметров
                    'cartCount' => Cart::getCountWhere_WithStatus('session', session_id()), //число товаров в корзине
                    'auth' => Users::is_auth(), //аутентифицирован ли пользователь
                    'login' => Users::getLogin(), //логин пользователя
                    'admin' => Users::is_admin() //является ли пользователь админом
                ]
            );
        } else {
            return $this->renderTemplate($template, $params);//если нет подслоя (layout), то страница просто рендерится
        }
    }

    public function renderTemplate($template, $params = []) {
        return $this->renderer->renderTemplate($template, $params);
    }

}