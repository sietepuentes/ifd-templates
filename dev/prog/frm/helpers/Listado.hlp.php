<?php

Jota::incluir(  array(  'helpers'=> array(  'JqGrid',
                                            'MySql'
                                        )
                    )
        );

class Listado
{

    // <editor-fold defaultstate="collapsed" desc="constantes">

    const CANTIDAD_RESULTADOS= 9999999; //TODO esto habria que mejorarlo, sacar el limit del procedure

// </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="metodos">

//    public static function obtenerListado($sSelectFrom1)
//    {
//        return self::_obtenerListado();
//    }

    public static function obtenerListado($sSelectFrom, $sWhere= null, $sOrderField= null, $sOrderDir= null)
    {
        return self::_obtenerListado($sSelectFrom, $sWhere, null, null, $sOrderField, $sOrderDir);
    }

    public static function obtenerListadoParaJqGrid($sSelectFrom, $sWhere= null,
            $page= null, $limit= null, /* paginacion */
            $sOrderField= null, $sOrderDir= null, /* ordenamiento */
            $sSearchField= null, $sSearchOperator= null, $sSearchValue= null, /* busqeuda de jqgrid */
            $sSelectFrom2= null, $sWhere2= null /* segundo select para hacer un union */
    )
    {
        return self::_obtenerListado($sSelectFrom, $sWhere, $page, $limit, $sOrderField, $sOrderDir, $sSearchField, $sSearchOperator, $sSearchValue, $sSelectFrom2, $sWhere2);
    }

        public static function obtenerListadoPaginado($sSelectFrom1, $sWhere1= null,
            $page= null, $limit= null, /* paginacion */
            $sOrderField= null, $sOrderDir= null, /* ordenamiento */
            $sSelectFrom2= null, $sWhere2= null /* segundo select para hacer un union */
    )
    {


        return self::_obtenerListado($sSelectFrom1, $sWhere1, $page, $limit, $sOrderField, $sOrderDir, null, null, null, $sSelectFrom2, $sWhere2);
    }

    /*private static function _obtenerListado($sSelectFrom1, $sWhere1= null,
            $page= null, $limit= null, //paginacion 
            $sOrderField= null, $sOrderDir= null, // ordenamiento 
            $sSearchField= null, $sSearchOperator= null, $sSearchValue= null, // busqeuda de jqgrid 
            $sSelectFrom2= null, $sWhere2= null // segundo select para hacer un union 
    )
    {
        $sSelectFrom1= self::sacarBarraEnes( $sSelectFrom1);
        $sWhere1= self::sacarBarraEnes( $sWhere1);
        $sSelectFrom2= self::sacarBarraEnes( $sSelectFrom2);
        $sWhere2= self::sacarBarraEnes( $sWhere2);



        //valores por default
        $page= Data::ifNull($page, 1);
        $limit= Data::ifNull($limit, self::CANTIDAD_RESULTADOS);
        $sOrderField= Data::ifNull($sOrderField, 'id');
        $sOrderDir= Data::ifNull($sOrderDir, 'ASC');
        //parametros que pueden ser nulos
        $sSearchField= (string)$sSearchField;
        $sSearchOperator= (string)$sSearchOperator;
        $sSearchValue= (string)$sSearchValue;
        $sWhere1= (string)$sWhere1;
        $sSelectFrom2= (string)$sSelectFrom2;
        $sWhere2= (string)$sWhere2;
        $bDebug= false;

        $sJqGridSearchCond= JqGrid::getSqlSearch($sSearchField, $sSearchOperator, $sSearchValue);

        $dbh = Database::getInstance();
        //esto es importantisimo
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

        $stmt = $dbh->prepare("CALL obtener_listado(".
                ":p_select_from1,".
                ":p_where1,".
                ":p_page,".
                ":p_limit,".
                ":p_order_field,".
                ":p_order_dir,".
                ":p_jqgrid_search_cond,".
                ":p_select_from2,".
                ":p_where2,".
                ":p_debug".
        ")");

        $stmt->bindParam(':p_select_from1', $sSelectFrom1, PDO::PARAM_STR);
        $stmt->bindParam(':p_where1', $sWhere1 , PDO::PARAM_STR);
        $stmt->bindParam(':p_page', $page, PDO::PARAM_INT);
        $stmt->bindParam(':p_limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':p_order_field', $sOrderField, PDO::PARAM_STR);
        $stmt->bindParam(':p_order_dir', $sOrderDir, PDO::PARAM_STR);
        $stmt->bindParam(':p_jqgrid_search_cond', $sJqGridSearchCond, PDO::PARAM_STR);
        $stmt->bindParam(':p_select_from2', $sSelectFrom2, PDO::PARAM_STR);
        $stmt->bindParam(':p_where2', $sWhere2 , PDO::PARAM_STR);
        $stmt->bindParam(':p_debug', $bDebug , PDO::PARAM_BOOL);

        //Jota::log('KERY', $stmt->getSQL());
        $stmt->execute();
        $arrRetorno = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

       // if(MySql::tieneResultados($arrRetorno))
       //     Jota::log('COLUMNAS', array_keys($arrRetorno[0]));
       
        return $arrRetorno;
    }*/

    
    private static function _obtenerListado($sSelectFrom1, $sWhere1= null,
            $page= null, $limit= null, //paginacion 
            $sOrderField= null, $sOrderDir= null, // ordenamiento 
            $sSearchField= null, $sSearchOperator= null, $sSearchValue= null, // busqeuda de jqgrid 
            $sSelectFrom2= null, $sWhere2= null // segundo select para hacer un union 
    )
    {
        $sSelectFrom1= self::sacarBarraEnes( $sSelectFrom1);
        $sWhere1= self::sacarBarraEnes( $sWhere1);
        $sSelectFrom2= self::sacarBarraEnes( $sSelectFrom2);
        $sWhere2= self::sacarBarraEnes( $sWhere2);

        //valores por default
        $page= Data::ifNull($page, 1);
        $limit= Data::ifNull($limit, self::CANTIDAD_RESULTADOS);
        $sOrderField= Data::ifNull($sOrderField, 'id');
        $sOrderDir= Data::ifNull($sOrderDir, 'ASC');
        //parametros que pueden ser nulos
        $sSearchField= (string)$sSearchField;
        $sSearchOperator= (string)$sSearchOperator;
        $sSearchValue= (string)$sSearchValue;
        $sWhere1= (string)$sWhere1;
        $sSelectFrom2= (string)$sSelectFrom2;
        $sWhere2= (string)$sWhere2;

        $sJqGridSearchCond= JqGrid::getSqlSearch($sSearchField, $sSearchOperator, $sSearchValue);

        $p_select_from1 = $sSelectFrom1;
        $p_where1 = $sWhere1;
        $p_page= $page;
        $p_limit = $limit;
        $p_order_field = $sOrderField;
        $p_order_dir = $sOrderDir;
        $p_jqgrid_search_cond = $sJqGridSearchCond;

        $from_stmt1 = "";

        $p_select_from1 = strtolower($p_select_from1);
        $p_where1 = strtolower($p_where1);
        $clausulas_where1= ' 1 = 1 ';

        IF(trim($p_where1)!= ''){
            $clausulas_where1=  $clausulas_where1. ' and '. $p_where1. ' ';
        }


        IF(trim($p_jqgrid_search_cond)!= '') {
            $clausulas_where1= $clausulas_where1. ' and '.$p_jqgrid_search_cond. ' ';
        }

        $aux = explode("from", $p_select_from1);
        $from_stmt1=$aux[1];

        //SET from_stmt1= SUBSTRING_INDEX(p_select_from1, ' from ', -1);

        $count_stmt= 'select count(1) as cant from '.$from_stmt1.' where '.$clausulas_where1;

        $oRes = PDOHelper::query($count_stmt);
        $cantidad = $oRes[0]['cant'];

        $cant_pags= ceil($cantidad / $p_limit);
        $START = $p_limit * $p_page - $p_limit;

        /*
        SET @select_size = -1;
        SET @posicion_from = 0;
        REPEAT 
        SET @select_size = @select_size + 1;
        SET @posicion_from = LOCATE(' from ', p_select_from1, @posicion_from+ 1); 
        UNTIL @posicion_from = 0 END REPEAT;
        */
        $aux = explode(" from ", $p_select_from1);
        $sql_select1=$aux[0];	
        //SET @sql_select1= SUBSTRING_INDEX(p_select_from1, ' from ', @select_size);


        $stmt_query_final= $sql_select1. ', '.$p_page. ' page, '.  $cant_pags. ' total, '. $cantidad. ' records from '.$from_stmt1.' where '.$clausulas_where1;


        IF(trim($p_order_field)!= ''){
            $stmt_query_final= $stmt_query_final.' ORDER BY '.$p_order_field. ' '. $p_order_dir;
        }
        $stmt_query_final= $stmt_query_final.' LIMIT '.$START.', '.$p_limit;	

        //echo $stmt_query_final;
        $arrRetorno = PDOHelper::query($stmt_query_final);

        return $arrRetorno;
    }
    
    
    
    
    /**
     * Jota: retorna un listado sin paginacion con un SP
     * @param <string> $procedure
     * @param <array> $parametros ej:array('fechaDesde'=> $fechaDesde,'fechaHasta'=> $fechaHasta)
     */
    public static function obtenerListadoSP($store, $parametros)
    {

        $db = Database::getInstance();
        $sql = '';
        $colNames = '';
        $registros = '';
        $arrValores = array();

        $sql .= '(';
        foreach ($parametros as $key => $val)
        {
           $sql .= "?, ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ')';

        $consulta = $db->prepare("call $store $sql");
       
        foreach ($parametros as $key => $val)
        {
                array_push($arrValores, $val);
        }

        $consulta->execute($arrValores);

        $rs = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $rs;
    }

    private static function sacarBarraEnes($sTexto)
    {
        $busqueda   = array("\r\n", "\n", "\r", "\t");
        $reemplazo = ' ';
        return str_replace($busqueda, $reemplazo, $sTexto);
    }

// </editor-fold>
}
?>
