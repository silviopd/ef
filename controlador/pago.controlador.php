<?php

    require_once '../negocio/Pago.class.php';
    require_once '../util/funciones/Funciones.class.php';
    $objArticulo = new Pago();
    $id = $_POST["p_id"];
    try
    {
        $registros = 
                $objArticulo->obtenerCuotasPrestamoID($id);
    } catch (Exception $exc)
    {
        Funciones::mensaje($exc->getMessage(), "e");
    }
    
?>

<table id="tabla-listado" class="table table-bordered table-striped">
    <thead>
            <tr>
                    <th>N. CUOTA</th>
                    <th>FECHA VENCIMIENTO</th>
                    <th>IMPORTE</th>
                    <th>SALDO</th>
                    <th>IMPORTE PAGADO</th>
                    
            </tr>
    </thead>
    <tbody id="detallecuotas">
        <?php
            for ($i=0; $i<count($registros);$i++) { 
                echo '<tr>';
                    echo '<td>'.$registros[$i]["numero_cuota"].'</td>';
                    echo '<td>'.$registros[$i]["fecha_vencimiento_cuota"].'</td>';
                    echo '<td>'.$registros[$i]["importe_cuota"].'</td>';
                    echo '<td>'.$registros[$i]["saldo_cuota"].'</td>';
                    echo '<td id ="cmontopagar">0</td>';
                echo '</tr>';
            }
        ?>
        
    </tbody>
</table>

    
    
    
    