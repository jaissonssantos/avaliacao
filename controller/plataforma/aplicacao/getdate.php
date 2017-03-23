<?php

header('Content-type: application/json');
$response = new stdClass();
date_default_timezone_set( 'America/Rio_Branco' ); //Acre, Rio branco

try {

	$ano = date('Y');
	$mes = date('m');
	$dia = date('d');
	$hora = date('H');
	$minuto = date('i');

	$results = new stdClass();
	$results->ano = $ano;
	$results->mes = $mes;
	$results->dia = $dia;
	$results->hora = $hora;
	$results->minuto = $minuto;

	http_response_code(200);
    if (!$results) {
        throw new Exception('Nenhum resultado encontrado', 404);
    }
    $response = array(
        'results' => $results
    );

} catch (Exception $e) {
	http_response_code($e->getCode());
	$response->error = $e->getMessage();
}

echo json_encode($response);