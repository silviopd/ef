<?php

require_once '../negocio/Cliente.php';

$dni = $_POST["p_dni"];

$objArticulo = new Cliente();
try
{
    $resultado = $objArticulo->obtenerClienteDni($dni);
    echo json_encode($resultado);
    //print_r($resultado);
} catch (Exception $exc)
{
    header("HTTP/1.1 500");
    echo $exc->getMessage();
}





