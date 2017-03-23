<?php

require_once 'controller.class.php';
require_once '../conn/conexao.class.php';
require_once '../utils/functions.php';
require_once '../utils/Facebook/autoload.php';
require_once '../utils/zenvia/Zenvia.php';
require_once 'ApiController.php';
require_once 'Exceptions/NotFoundException.php';
require_once '../vendor/autoload.php';
$routes = require_once 'routes.php';

$s3 = new \Aws\S3\S3Client([
		'version' => 'latest',
		'region'  => 'us-east-1',
		'endpoint' => STORAGE_HOST,
		'credentials' => [
			'key'    => STORAGE_KEY,
			'secret' => STORAGE_SECRET,
		],
]);
$s3->registerStreamWrapper();

use Base\Controller;

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
