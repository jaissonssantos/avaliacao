<?php

class TestController extends ApiController
{
    public function index()
    {
    	$sql = 'SELECT DISTINCT seg.nome,seg.hash
				FROM publicidade pub
				LEFT JOIN estabelecimento es ON(pub.idestabelecimento = es.id)
				LEFT JOIN segmento seg ON(es.idsegmento = seg.id)
				WHERE pub.status = 1
		        AND NOW() BETWEEN data_inicio AND data_fim
				ORDER BY pub.ordenacao ASC';
		$dadosWhere = [];
    	
    	$data = Transactions::selectRaw( $sql, $dadosWhere );

    	if(count($data) > 0)
        	return $this->respond($data, 201);

        throw new NotFoundException("Nada Encontrado", 1);
    }
}
