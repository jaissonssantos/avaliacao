<?php

use Base\Controller;

require_once 'controller.class.php';
require_once '../conn/conexao.class.php';
require_once '../vendor/functions.php';
require_once 'ApiController.php';
require_once 'Exceptions/NotFoundException.php';
$routes = require_once 'routes.php';

$pathController = $_GET['path_controller'];

if (! array_key_exists($pathController, $routes)) {
    new Controller\BaseController($pathController);
} else {
	$route = $routes[$pathController];
	$method = $route['method'];

	require_once "{$route['path']}/{$route['class']}.php";

	try {
		return (new $route['class'])->$method();
	} catch(NotFoundException $e) {
		header('Content-type: application/json');
		http_response_code(404);
		echo json_encode($response['error'] = $e->getMessage());
	} catch(Exception $e) {
		header('Content-type: application/json');
		http_response_code(500);
		$data = DEBUG ? $e->getMessage() : 'Desculpe. Tivemos um problema, tente novamente mais tarde';
		echo json_encode($response['error'] = $data);
	}
}
