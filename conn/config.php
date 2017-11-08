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
define('URL_SITE', 'http://avaliame.dev');
define('TITLE_APP', 'Questionários e pesquisas online -  Avaliame');
define('FOOTER_APP', 'Avalime.com');
define('EMAIL', 'contato@avaliame.com.br');
define('DEBUG', false);

//template do dashboard
//http://eliteadmin.themedesigner.in/demos/bootstrap4/elite-hospital/index.html?

