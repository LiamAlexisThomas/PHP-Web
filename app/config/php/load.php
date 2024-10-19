<?php

require 'config.php';

$columns = ['id' , 'nombre' , 'elemento' , 'nacion' , 'arma' , 'ver_ing'];
$id = 'id';

$table = "personajes";

$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : null;

$where = ' ';

if($campo != null){
    $where = "WHERE (";
    $cont = count($columns);
    for($i = 0 ; $i < $cont ; $i++){
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }
    $where = substr_replace($where, "", -3);
    $where.= ")";
}

$limit = isset($_POST['registros']) ? $conn->real_escape_string($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? $conn->real_escape_string($_POST['pagina']) : 0;

if (!$pagina){
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $limit;
}

$sLimit = "LIMIT $inicio , $limit";

$sOrder = "";
if(isset($_POST['orderCol'])){
    $orderCol = $_POST['orderCol'];
    $orderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';

    $sOrder = "ORDER BY ". $columns[intval($orderCol)] . ' ' . $orderType;
}

$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(" , ", $columns) . " FROM $table $where $sOrder $sLimit ";
$resultado = $conn->query($sql);
$num_rows = $resultado->num_rows;

$sqlFiltro = "SELECT FOUND_ROWS()";
$resFiltro = $conn->query($sqlFiltro);
$rowFiltro = $resFiltro->fetch_array();
$filtro = $rowFiltro[0];

$sqlTotal = "SELECT count($id) FROM $table";
$resTotal = $conn->query($sqlTotal);
$rowTotal = $resTotal->fetch_array();
$total = $rowTotal[0];

$output = [];
$output['total'] = $total;
$output['filtro'] = $filtro;
$output['data'] = ' ';
$output['paginacion'] = ' ';

if($num_rows > 0){
    while($row = $resultado->fetch_assoc()){
        $output['data'] .= '<tr>';
        $output['data'] .= '<td>'.$row['id'].'</td>';
        $output['data'] .= '<td>'.$row['nombre'].'</td>';
        $output['data'] .= '<td>'.$row['elemento'].'</td>';
        $output['data'] .= '<td>'.$row['nacion'].'</td>';
        $output['data'] .= '<td>'.$row['arma'].'</td>';
        $output['data'] .= '<td>'.$row['ver_ing'].'</td>';
        $output['data'] .= '<td><a class="btn btn-warning btn-sm" href="editar.php?id=' . $row['id'] . ' ">Editar</a></td>';
        $output['data'] .= "<td><a class='btn btn-danger btn-sm' href='eliminar.php?id=" . $row['id'] . " '>Eliminar</a></td>";
        $output['data'] .= '</tr>';
    }
} else {
    $output['data'] .= '<tr>';
    $output['data'] .= '<td colspan="10">Sin resultados.</td>';
    $output['data'] .= '</tr>';
}

if ($output['total'] > 0){
    $totalPag = ceil($output['total'] / $limit);

    $output['paginacion'] .= '<nav>';
    $output['paginacion'] .= '<ul class="pagination">';

    $inicio = 1;

    if(($pagina - 4) > 1){
        $inicio = ($pagina - 4);
    }

    $fin = $inicio + 9;

    if($fin > $total){
        $fin = $total;
    }

    for($i = 1 ; $i <= $totalPag ; $i++){
        if($pagina == $i){
            $output['paginacion'] .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="nextPage(' . $i . ')">' . $i . '</a></li>';
        }
    }
    $output['paginacion'] .= '</ul>';
    $output['paginacion'] .= '</nav>';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);