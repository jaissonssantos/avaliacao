<?php

require_once('../utils/iugu/Iugu.php');

class IuguTestController extends ApiController
{
    public function index()
    {
    	try {
    		$iugu = IuguConnect::charge('pinhojoao@gmail.com', '10/12/2016', 'Cobrança de Teste', 1, 1000);

    		die(var_dump($iugu));
    	} catch(Exception $e) {
    		die($e->getMessage());
    	}
    }
}
