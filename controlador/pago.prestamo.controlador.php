<?php

    require_once '../negocio/Pago.class.php';
    require_once '../util/funciones/Funciones.class.php';

$dni = $_POST['p_dni'];
    $objLinea = new Pago();
    try {
        $resultado = $objLinea->obtenerPrestamos($dni);
    } catch (Exception $exc) {
        Funciones::mensaje($exc->getMessage(), "e");
    }
    

    echo '<option value="">Selecione un préstamo</option>';

    for ($i=0; $i<count($resultado); $i++)
    {
        $imprimir = "Préstamo N° {$resultado[$i]["numero_prestamo"]} - Fecha: {$resultado[$i]["fecha_prestamo"]} - ";
        $imprimir .= "Importe: {$resultado[$i]["importe"]} - N. Cuotas: {$resultado[$i]["numero_cuotas"]}";
        echo '<option value="'.$resultado[$i]["numero_prestamo"].'">'.$imprimir.'</option>';
    }


    
    
    

