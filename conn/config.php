<?php

//ocultar os warning e alerts do php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);

//definindo os dados de acesso ao banco de dados
define('DB', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'avaliame');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('SALT', 'x&$29#*s=!1bm'); //nunca mudar(salt padrão)

//definindo as URLs padrões do sistema e site
define('URL_SITE', 'http://avaliacao.dev');
define('TITLE_APP', 'Avalia.me');
define('EMAIL', 'contato@avalia.me');
define('DEBUG', false);

//http://eliteadmin.themedesigner.in/demos/bootstrap4/elite-hospital/index.html?

