<?php

//ocultar os warning e alerts do php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);

//definindo os dados de acesso ao banco de dados
define('DB', 'mysql');

define('DB_HOST', '104.131.74.63');
//define('DB_HOST', 'localhost');

define('DB_NAME', 'markday');
define('DB_USER', 'root');
//define('DB_PASS', '');
define('DB_PASS','431sxcbeskoa');

define('SALT', 'h&p*wm=zo!'); //nunca mudar(salt padrão)
define('EMAIL_NOREPLAY', 'nao-responda@markday.com.br');

//definindo as URLs padrões do sistema e site
define('URL_APP', 'https://markday.com.br');
define('URL_SITE', 'https://markday.com.br');
define('TITLE_APP', 'MARKDAY');
define('DEBUG', false);

// Configurações API Facebook
define('FACEBOOK_APP_ID', '1204197739647411');
define('FACEBOOK_APP_SECRET', '0f4b15950b4c2a1c0b6b606c8a487ad0');

// Storage
define('STORAGE_URL', 'https://markday.imgix.net');
define('STORAGE_HOST', 'https://markday.com.br:2053');
define('STORAGE_KEY', 'asWWxGfsfvnfgd');
define('STORAGE_SECRET', 'dfvSsdHwvyjrpgGHFsqFrsdcEdccB');

//IUGU
define('IUGU_KEY', '76a9f9d99f5ade6e093d255d95d97726');