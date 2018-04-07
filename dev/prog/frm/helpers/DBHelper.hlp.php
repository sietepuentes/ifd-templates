<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB
 *
 * @author Luki
 */
Jota::incluir(array(
                    "helpers"   => array("MySql")
                    )
);
class DBHelper {
    //put your code here

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
			$sQueryLlamado.= ":" . $sNombre;
			$sQueryLlamado.= ($i< count($arrParametros)- 1? ',': '');
		}
		$sQueryLlamado.= ")";

		/* @var $dbh Database */
		$dbh = Database::getInstance();

		/* @var $stmt PDOStatementTester */
		$stmt = $dbh->prepare( $sQueryLlamado );

                //PARCHE PARA EL FCKEdit, es un parche feo pero aca deberia aparecer cada parametro de fckedit
                if(isset($arrParametros["p_texto"]))
                    $arrParametros["p_texto"] = str_replace ("\'", ";", $arrParametros["p_texto"]);
                if(isset($arrParametros["p_presentacionexpositor"]))
                    $arrParametros["p_presentacionexpositor"] = str_replace ("\'", ";", $arrParametros["p_presentacionexpositor"]);
                if(isset($arrParametros["p_ubicadoen"]))
                    $arrParametros["p_ubicadoen"] = str_replace ("\'", ";", $arrParametros["p_ubicadoen"]);
                if(isset($arrParametros["p_operadores"]))
                    $arrParametros["p_operadores"] = str_replace ("\'", ";", $arrParametros["p_operadores"]);
                if(isset($arrParametros["p_logros"]))
                    $arrParametros["p_logros"] = str_replace ("\'", ";", $arrParametros["p_logros"]);
                if(isset($arrParametros["p_aprendizajes"]))
                    $arrParametros["p_aprendizajes"] = str_replace ("\'", ";", $arrParametros["p_aprendizajes"]);
                if(isset($arrParametros["p_requisitos"]))
                    $arrParametros["p_requisitos"] = str_replace ("\'", ";", $arrParametros["p_requisitos"]);
                if(isset($arrParametros["p_infoimportante"]))
                    $arrParametros["p_infoimportante"] = str_replace ("\'", ";", $arrParametros["p_infoimportante"]);
                if(isset($arrParametros["p_beneficios"]))
                    $arrParametros["p_beneficios"] = str_replace ("\'", ";", $arrParametros["p_beneficios"]);
                if(isset($arrParametros["p_bajada"]))
                    $arrParametros["p_bajada"] = str_replace ("\'", ";", $arrParametros["p_bajada"]);
                if(isset($arrParametros["p_texto2"]))
                    $arrParametros["p_texto2"] = str_replace ("\'", ";", $arrParametros["p_texto2"]);
                if(isset($arrParametros["p_textoizquierdo"]))
                    $arrParametros["p_textoizquierdo"] = str_replace ("\'", ";", $arrParametros["p_textoizquierdo"]);
                if(isset($arrParametros["p_textoderecho"]))
                    $arrParametros["p_textoderecho"] = str_replace ("\'", ";", $arrParametros["p_textoderecho"]);


				if(isset($arrParametros["p_texto"]))
                    $arrParametros["p_texto"] = str_replace ("\Z", '"', $arrParametros["p_texto"]);
                if(isset($arrParametros["p_presentacionexpositor"]))
                    $arrParametros["p_presentacionexpositor"] = str_replace ("\Z", '"', $arrParametros["p_presentacionexpositor"]);
                if(isset($arrParametros["p_ubicadoen"]))
                    $arrParametros["p_ubicadoen"] = str_replace ("\Z", '"', $arrParametros["p_ubicadoen"]);
                if(isset($arrParametros["p_operadores"]))
                    $arrParametros["p_operadores"] = str_replace ("\Z", '"', $arrParametros["p_operadores"]);
                if(isset($arrParametros["p_logros"]))
                    $arrParametros["p_logros"] = str_replace ("\Z", '"', $arrParametros["p_logros"]);
                if(isset($arrParametros["p_aprendizajes"]))
                    $arrParametros["p_aprendizajes"] = str_replace ("\Z", '"', $arrParametros["p_aprendizajes"]);
                if(isset($arrParametros["p_requisitos"]))
                    $arrParametros["p_requisitos"] = str_replace ("\Z", '"', $arrParametros["p_requisitos"]);
                if(isset($arrParametros["p_infoimportante"]))
                    $arrParametros["p_infoimportante"] = str_replace ("\Z", '"', $arrParametros["p_infoimportante"]);
                if(isset($arrParametros["p_beneficios"]))
                    $arrParametros["p_beneficios"] = str_replace ("\Z", '"', $arrParametros["p_beneficios"]);
                if(isset($arrParametros["p_bajada"]))
                    $arrParametros["p_bajada"] = str_replace ("\Z", '"', $arrParametros["p_bajada"]);
                if(isset($arrParametros["p_tagvideo"]))
                    $arrParametros["p_tagvideo"] = str_replace ("\Z", '"', $arrParametros["p_tagvideo"]);
                if(isset($arrParametros["p_tagvideodescarga"]))
                    $arrParametros["p_tagvideodescarga"] = str_replace ("\Z", '"', $arrParametros["p_tagvideodescarga"]);
                if(isset($arrParametros["p_texto2"]))
                    $arrParametros["p_texto2"] = str_replace ("\Z", '"', $arrParametros["p_texto2"]);
                if(isset($arrParametros["p_textoizquierdo"]))
                    $arrParametros["p_textoizquierdo"] = str_replace ("\Z", '"', $arrParametros["p_textoizquierdo"]);
                if(isset($arrParametros["p_textoderecho"]))
                    $arrParametros["p_textoderecho"] = str_replace ("\Z", '"', $arrParametros["p_textoderecho"]);
                //exit;

				if(isset($arrParametros["p_texto"]))
                    $arrParametros["p_texto"] = str_replace ("\\", '', $arrParametros["p_texto"]);
                if(isset($arrParametros["p_presentacionexpositor"]))
                    $arrParametros["p_presentacionexpositor"] = str_replace ("\\", '', $arrParametros["p_presentacionexpositor"]);
                if(isset($arrParametros["p_ubicadoen"]))
                    $arrParametros["p_ubicadoen"] = str_replace ("\\", '', $arrParametros["p_ubicadoen"]);
                if(isset($arrParametros["p_operadores"]))
                    $arrParametros["p_operadores"] = str_replace ("\\", '', $arrParametros["p_operadores"]);
                if(isset($arrParametros["p_logros"]))
                    $arrParametros["p_logros"] = str_replace ("\\", '', $arrParametros["p_logros"]);
                if(isset($arrParametros["p_aprendizajes"]))
                    $arrParametros["p_aprendizajes"] = str_replace ("\\", '', $arrParametros["p_aprendizajes"]);
                if(isset($arrParametros["p_requisitos"]))
                    $arrParametros["p_requisitos"] = str_replace ("\\", '', $arrParametros["p_requisitos"]);
                if(isset($arrParametros["p_infoimportante"]))
                    $arrParametros["p_infoimportante"] = str_replace ("\\", '', $arrParametros["p_infoimportante"]);
                if(isset($arrParametros["p_beneficios"]))
                    $arrParametros["p_beneficios"] = str_replace ("\\", '', $arrParametros["p_beneficios"]);
                if(isset($arrParametros["p_bajada"]))
                    $arrParametros["p_bajada"] = str_replace ("\\", '', $arrParametros["p_bajada"]);
                if(isset($arrParametros["p_tagvideo"]))
                    $arrParametros["p_tagvideo"] = str_replace ("\\", '', $arrParametros["p_tagvideo"]);
                if(isset($arrParametros["p_tagvideodescarga"]))
                    $arrParametros["p_tagvideodescarga"] = str_replace ("\\", '', $arrParametros["p_tagvideodescarga"]);
                if(isset($arrParametros["p_texto2"]))
                    $arrParametros["p_texto2"] = str_replace ("\\", '', $arrParametros["p_texto2"]);
                if(isset($arrParametros["p_textoizquierdo"]))
                    $arrParametros["p_textoizquierdo"] = str_replace ("\\", '', $arrParametros["p_textoizquierdo"]);
                if(isset($arrParametros["p_textoderecho"]))
                    $arrParametros["p_textoderecho"] = str_replace ("\\", '', $arrParametros["p_textoderecho"]);
                Jota::log( 'procedure', $stmt->getSQL( $arrParametros) );
//                echo $stmt->getSQL( $arrParametros);
//exit;
		$stmt->execute( $arrParametros );
		$arrRetorno = $stmt->fetchAll( PDO::FETCH_ASSOC );
		$stmt->closeCursor();

		return $arrRetorno;
	}

        public static function query($sql)
	{
            $dbh = Database::getInstance(USAR_PDO);
            if (USAR_PDO){
                /* @var $dbh Database */
                $dbh = Database::getInstance();
                /* @var $stmt PDOStatementTester */
                $stmt = $dbh->prepare($sql);
                //Jota::log('query', $sql);
                //Jota::log('bd', $dbh);
                //Jota::log('sbh', $stmt);

                $stmt->execute();
                $arrRetorno = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
            }
            else
            {

                $dbh->query($sql);

                while ($line = $dbh->fetchNextObject()) {
                    $arrRetorno[] = (array)$line;
                }

                
                
            }
	        return $arrRetorno;
	}
        public static function execute($sql)
	{
            $dbh = Database::getInstance(USAR_PDO);
            if (USAR_PDO){
                $dbh = Database::getInstance();
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $arrRetorno = ($dbh->lastInsertId() > 0 ? $dbh->lastInsertId() : 0);
                $stmt->closeCursor();
            }
            else
            {
               $dbh->execute($sql);
               $id = $dbh->lastInsertedId();
               $arrRetorno = ($dbh->lastInsertedId() > 0 ? $dbh->lastInsertedId() : 0);
            }
	    return $arrRetorno;
	}

}
?>
