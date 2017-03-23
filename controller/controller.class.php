<?php

namespace Base\Controller;

class BaseController
{
    public function __construct($path)
    {
        session_start();
        define('BASE_DIR', realpath('../'));
        $this->loadController($path);
    }

    private function loadController($path)
    {
        if (strpos($path, '..') !== false) {
            return;
        }
        $filePath = './'.$path.'.php';
        if (file_exists($filePath)) {
            include_once $filePath;
        }
    }
}
