<?php

abstract class JqGrid
{
    public static function armarJson($resultados)
    {
        $page= null;
        $total = null;
        $records= null;
        
//        if(!Data::vacio($resultados && count($resultados)> 0))
//        {
            $page= isset ($resultados[0]['page'])?$resultados[0]['page']:1;
            $total= isset($resultados[0]['total'])?$resultados[0]['total']:1;
            $records= isset($resultados[0]['records'])?$resultados[0]['records']:count($resultados);

            for($i= 0; $i<= count($resultados)-1; $i++)
            {
                if (isset ($resultados[0]['page'])) unset($resultados[$i]['page']);
                if (isset ($resultados[$i]['total']))unset($resultados[$i]['total']);
                if (isset ($resultados[$i]['records']))unset($resultados[$i]['records']);

                $resultados[$i] = array_values($resultados[$i]);

            }
//        }


        $jason= array( 'page'=> $page,
                        'total'=> $total,
                        'records'=> $records,
                        'rows'=> $resultados
            );
      
        return json_encode($jason);
    }

    public static function getSqlSearch($searchField, $searchOper, $searchString)
    {
        $sqlSearch="";
        if ($searchField != "") {
            switch ($searchOper) {
                case 'eq': $searchCondition = " = '$searchString' ";
                    break;
                case 'ne': $searchCondition = '!= "' . $searchString . '"';
                    break;
                case 'bw': $searchCondition = 'LIKE "' . $searchString . '%"';
                    break;
                case 'ew': $searchCondition = 'LIKE "%' . $searchString . '"';
                    break;
                case 'cn': $searchCondition = 'LIKE "%' . $searchString . '%"';
                    break;
                case 'lt': $searchCondition = '< "' . $searchString . '"';
                    break;
                case 'gt': $searchCondition = '> "' . $searchString . '"';
                    break;
                case 'le': $searchCondition = '<= "' . $searchString . '"';
                    break;
                case 'ge': $searchCondition = '>= "' . $searchString . '"';
                    break;
            }
            $sqlSearch=  $searchField . $searchCondition;
        }

        return $sqlSearch;
    }
}
?>
