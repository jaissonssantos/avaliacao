<?php

use Utils;

ob_start();
session_start();

require_once 'conn/conexao.class.php';
require_once 'conn/url.class.php';
require_once 'vendor/functions.php';

?>

<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br"> 
<![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
<!--<![endif]-->
<head>
<!-- Basic Page Needs -->
<meta charset="utf-8">
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title><?=TITLE_APP?></title>
<base href="/">
<meta name="description" content="Avalia.me sua avaliação em poucos cliques e resultado desejado">
<meta name="keywords" content="Avalia.me sua avaliação em poucos cliques e resultado desejado">
<meta name="author" content="">
<!-- Mobile Specific Metas -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" type="text/css" href="main.css">
<link href="assets/images/favicon.png" rel="shortcut icon" type="image/png" sizes="16x16">
<!-- Javascript -->
<script type="text/javascript" src="assets/javascript/jquery.min.js"></script>
<script type="text/javascript" src="assets/javascript/jquery.livequery.min.js"></script>
</head>

<body>

<?php
$path = Url::getURL(0);
$subpath = Url::getURL(1);
$file = Url::getURL(2);
$params = Url::getURL(3);

//route url
if(empty($path)){
	include 'views/home.php';
}else if(file_exists('views/'.$path.'.php')){
	include 'views/'.$path.'.php';
}else if(file_exists('views/'.$path.'/'.$subpath.'.php')){
	include 'views/'.$path.'/'.$subpath.'.php';
}
?>

<!-- Javascript -->
<script type="text/javascript" src="assets/javascript/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/javascript/app.js"></script>


</body>
</html>