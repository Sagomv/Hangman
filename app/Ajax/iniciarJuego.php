<?php

    $idCategoria = ($_POST['idCategoria'] > 0 ? $_POST['idCategoria'] : 1);

    $sub = DB::Select('Select nombreSubCategoria from subcategoria where Categoria_idCategoria = '.$idCategoria);
    $nomb = '';
    $pos = rand(0, count($sub) -1); 
    $nombre = get_object_vars($sub[$pos])['nombreSubCategoria'];
    
    echo json_encode($nombre);
?>