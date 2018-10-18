<?php

require_once '../negocio/Cliente.php';

$valorBusqueda = $_GET["term"];

if (!$valorBusqueda)
{
    return;
}

$objProveedor = new Cliente();
try {
    $resultado = 
            $objProveedor->obtenerClienteNombre($valorBusqueda);
    
    $retorno = array();
    
    for ($i = 0; $i < count($resultado); $i++) {
        $datos = array
            (
                "label" => $resultado[$i]["nombrecliente"],
                "value" => array(
                    "dni" => $resultado[$i]["dni_cliente"],
                    "nc"  => $resultado[$i]["nombrecliente"],
                    "dir" => $resultado[$i]["direccion"],
                    "telf" => $resultado[$i]["telefono"]
                )
            );
        $retorno[$i] = $datos;
    }
    
    echo json_encode($retorno);
    
    
    
} catch (Exception $exc) {
    echo $exc->getMessage();
}

