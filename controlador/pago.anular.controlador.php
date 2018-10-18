<?php

require_once '../negocio/Pago.class.php';

$numeroPago = $_POST["p_nro_pago"];
$objPago = new Pago();

try
{
    $objPago->setNumeroPago($numeroPago);
    echo ($objPago->anular())?'exito':'Imposible eliminar';

} catch (Exception $exc)
{
    header("HTTP/1.1 500");
    echo $exc->getMessage();
}
