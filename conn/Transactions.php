<?php

use Utils\Conexao;

final class Transactions
{
	private static $conn;

	public static function getConn() {
		if(empty(self::$conn)) {
			self::$conn = Conexao::getInstance();
		}
		return self::$conn;
	}

	public static function selectRaw( $sql, $dadosWhere ) {
		$con = self::getConn();
		$separados = array();
		foreach( $dadosWhere as $dw ) {			
			$separados[] = self::transform($dw);
		}
		$retorno = array();
		if( $stmt = $con->prepare($sql) ) {
			$stmt->execute($separados);
			while ( $ret = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				$retorno[] = $ret;
			}
		}
		return $retorno;
	}

	public static function raw( $sql, $dadosWhere ) { //Usado para inserts, updates e deletes
		$con = self::getConn();
		$separados = array();
		foreach( $dadosWhere as $dw ) {			
			$separados[] = self::transform($dw);
		}
		if( $stmt = $con->prepare($sql) ) {
			$stmt->execute($separados);
		}
	}

	public static function select( $tabela, $dados, $where, $dadosWhere ) {
		$con = self::getConn();
		//Adiciona os valores das ? do where no separados.
		$separados = array();
		foreach( $dadosWhere as $dw ) {			
			$separados[] = self::transform($dw);
		}
		$ds = join(', ', $dados);
		$sql = "SELECT $ds FROM $tabela";
		if($where != '') {
			$sql .= " WHERE " . $where;
		}
		$retorno = array();
		if( $stmt = $con->prepare($sql) ) {
			$stmt->execute($separados);
			while ( $ret = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				$retorno[] = $ret;
			}
		}
		return $retorno;
	}

	public static function insert( $tabela, $dados ) {
		$con = self::getConn();
		//Pega as chaves para poder usar o join
		$chaves = array_keys($dados);
		//Separa com vírgulas
		$campos = join(',', $chaves);
		$separados = array();
		$ints = array();
		foreach( $dados as $d ) {
			$ints[] = '?';
			$separados[] = self::transform($d);
		}
		$statement = join(',', $ints);
		$sql = "INSERT INTO $tabela ( $campos ) VALUES ( $statement );";
		if( $stmt = $con->prepare($sql) ) {
			$stmt->execute($separados);
		}
		return $con->lastInsertId();
	}
	public static function update( $tabela, $dados, $where, $dadosWhere ) {
		$con = self::getConn();
		//array com as chaves. Ex.: cada posicao no array terá algo do tipo: nome=?
		$valores = array();
		//os valores do array sem os índices (chaves) originais do array. Ex.: Ao invés de $dados['nome'], $separados[0].
		$separados = array();
		foreach( $dados as $chave=>$d ) {
			$valores[] = "$chave = ?";
			$separados[] = self::transform($d);
		}
		//$campos separados por ','. Exemplo: nome=?, idade=?
		$campos = join(',', $valores);
		//Adiciona os valores das ? do where no separados.
		foreach( $dadosWhere as $dw ) {
			$separados[] = self::transform($dw);
		}
		$sql = "UPDATE $tabela SET $campos WHERE $where";
		if( $stmt = $con->prepare($sql) ) {
			$stmt->execute($separados);
		}
	}
	public static function delete( $tabela, $where, $dadosWhere ) {
		$con = self::getConn();
		//Adiciona os valores das ? do where no separados.
		foreach( $dadosWhere as $dw ) {
			$separados[] = self::transform($dw);
		}
		$sql = "DELETE FROM $tabela WHERE $where";
		if( $stmt = $con->prepare($sql) ) {
			$stmt->execute($separados);
		}
	}

	private static function transform($value) {
		// return strip_tags($value, '<br><br />');
		return $value;
	}
}