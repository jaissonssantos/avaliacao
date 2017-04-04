<?php

//ocultar os warning e alerts do php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);

//definindo os dados de acesso ao banco de dados
define('DB', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'avaliacao');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('SALT', 'x&$29#*s=!1bm'); //nunca mudar(salt padrão)

//definindo as URLs padrões do sistema e site
define('URL_APP', 'https://avalia.me');
define('URL_SITE', 'https://avalia.me');
define('TITLE_APP', 'Avalia.me');
define('EMAIL_NOREPLAY', 'noreply-chat@avalia.me');
define('DEBUG', false);

// Configurações API Facebook
define('FACEBOOK_APP_ID', '1204197739647411');
define('FACEBOOK_APP_SECRET', '0f4b15950b4c2a1c0b6b606c8a487ad0');

// Storage
// define('STORAGE_URL', 'https://markday.imgix.net');
// define('STORAGE_HOST', 'https://markday.com.br:2053');
// define('STORAGE_KEY', 'asWWxGfsfvnfgd');
// define('STORAGE_SECRET', 'dfvSsdHwvyjrpgGHFsqFrsdcEdccB');
