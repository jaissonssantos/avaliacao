<?php

use Utils\Conexao;

$oConexao = Conexao::getInstance();

//params
$params = json_decode(file_get_contents('php://input'));

//get session local browser
$idestabelecimento = $_SESSION['ang_plataforma_estabelecimento'];
$response = new stdClass();

try {
    $stmt = $oConexao->prepare(
        'SELECT count(id) as total 
			FROM estabelecimento_configuracao
		WHERE idestabelecimento = :idestabelecimento'
    );
    $stmt->bindValue('idestabelecimento', $idestabelecimento);
    $stmt->execute();
    $agendamento = $stmt->fetchObject();
    if ($agendamento->total) {
        $params->message = isset($params->message) && $params->message != '' ? $params->message : 'Pague diretamente no estabelecimento, se necessário confirme os meios de pagamento aceitos.';

        $stmt = $oConexao->prepare(
                'UPDATE estabelecimento_configuracao
					SET campomsgpgtoobrigatorio=:messagerequired, campomsgpgtodescricao=:message
				WHERE idestabelecimento=:idestabelecimento'
        );
        $stmt->bindValue(':messagerequired', $params->messagerequired);
        $stmt->bindValue(':message', $params->message);
        $stmt->bindValue(':idestabelecimento', $idestabelecimento);
        $stmt->execute();

        http_response_code(200);
        $response->success = 'Atualizado com sucesso';
    } else {
        $stmt = $oConexao->prepare(
                'INSERT INTO estabelecimento_configuracao (campomsgpgtoobrigatorio, campomsgpgtodescricao)
					VALUES(:messagerequired, :message)'
        );
        $stmt->bindValue(':messagerequired', $params->messagerequired);
        $stmt->bindValue(':message', $params->message);
        $stmt->execute();

        http_response_code(200);
        $response->success = 'Cadastrado com sucesso';
    }
} catch (PDOException $e) {
    http_response_code(500);
    $response->error = 'Desculpa. Tivemos um problema, tente novamente mais tarde';
} catch (Exception $e) {
    http_response_code($e->getCode());
    $response->error = $e->getMessage();
}

echo json_encode($response);
