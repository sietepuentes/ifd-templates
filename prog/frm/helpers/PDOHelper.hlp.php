<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PDOHelper
 *
 * @author dami
 */
class PDOHelper
{
	/**
	 *
	 * @param string $sNombreProcedure El nombre del procedure
	 * @param array $arrParametros El array de parametros
	 * @return array
	 */
	public static function obtenerResultadosByProcedure($sNombreProcedure, $arrParametros)
	{
		$arrRetorno= null;

        $arrParametros= Data::sanitize($arrParametros);

		$sQueryLlamado= "CALL $sNombreProcedure(";
		$i= -1;
		foreach ($arrParametros as $sNombre => $valor)
		{
			$i++;
			$sQueryLlamado.= $sNombre;
			$sQueryLlamado.= ($i< count($arrParametros)- 1? ',': '');
		}
		$sQueryLlamado.= ")";

		/* @var $dbh Database */
		$dbh = Database::getInstance();

		/* @var $stmt PDOStatementTester */
		$stmt = $dbh->prepare( $sQueryLlamado );

		Jota::log( 'procedure', $stmt->getSQL( $arrParametros) );

		$stmt->execute( $arrParametros );
		$arrRetorno = $stmt->fetchAll( PDO::FETCH_ASSOC );
		$stmt->closeCursor();

		return $arrRetorno;
	}
	
        public static function query($sql) 
	{

	        /* @var $dbh Database */
	        $dbh = Database::getInstance();
	        /* @var $stmt PDOStatementTester */
	        $stmt = $dbh->prepare($sql);
	        //Jota::log('query', $sql);

	        $stmt->execute();              
	        $arrRetorno = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        $stmt->closeCursor();

                $dbh=null;

                return $arrRetorno;
	}
        
        public static function queryInsert($sql) 
	{

	        /* @var $dbh Database */
	        $dbh = Database::getInstance();
	        /* @var $stmt PDOStatementTester */
	        $stmt = $dbh->prepare($sql);
	        //Jota::log('query', $sql);

	        $stmt->execute();              
	        $stmt->closeCursor();

                $dbh=null;

                return true;
	}        
}

?>
