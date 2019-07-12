<?php

class Autoload
{
    public function loadClass($className) {

        $fileName = str_replace(["app\\", "\\"], [DIR_ROOT . "/../", DS], $className) . '.php';
        //echo 'преобразование прошло <br> строка ' . $className . ' преобразована в ' . $fileName . '<br>';
        include $fileName;
    }
}