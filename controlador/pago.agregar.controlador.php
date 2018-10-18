<?php

require_once '../negocio/Pago.class.php';

parse_str($_POST["p_array_datos_cabecera"], $datosCabecera);
$datosDetalle = $_POST["p_json_datos_detalle"];

$objPago = new Pago();
$objPago->setNumeroPrestamo($datosCabecera["cboprestamos"]);
$objPago->setDnCliente($datosCabecera["txtdni"]);
$objPago->setFechaPago($datosCabecera["txtfechapago"]);
$objPago->setTotalpagado($datosCabecera["txttotalpagado"]);
$objPago->setDetalle( $datosDetalle );

try
{
    $respuesta = $objPago->agregar();
    echo ($respuesta===true)?'exito':'No puedes registrar un pago si el importe total pagado es igual a cero.';
} catch (Exception $exc) {
    header("HTTP/1.1 500");
    echo $exc->getMessage();
}

