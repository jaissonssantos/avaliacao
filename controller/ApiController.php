<?php

require_once '../conn/Transactions.php';

abstract class ApiController 
{
	protected $params;

	public function __construct()
	{
		$this->params = json_decode(file_get_contents('php://input'));
	}

	protected function respond($data, $code = 200)
	{
		header('Content-type: application/json');
		http_response_code($code);
		echo json_encode($data);
	}
}