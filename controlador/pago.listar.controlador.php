<?php

    require_once '../negocio/Pago.class.php';
    require_once '../util/funciones/Funciones.class.php';

    $fecha1 = $_POST["p_fecha1"];
    $fecha2 = $_POST["p_fecha2"];
    $tipo   = $_POST["p_tipo"];

    $objVenta = new Pago();

    try {
        $registros = $objVenta->listar($fecha1, $fecha2, $tipo);
    } catch (Exception $exc)
    {
        Funciones::mensaje($exc->getMessage(), "e");
    }

?>

    <table id="tabla-listado" class="table table-bordered table-striped">
        <thead>
                <tr>
                        <th>&nbsp;</th>
                        <th>N. PAGO</th>
                        <th>FECHA PAGO</th>
                        <th>CLIENTE</th>
                        <th>N. PRÉSTAMO</th>
                        <th>FECHA PRÉSTAMO</th>
                        <th>TOTAL PAGADO</th>
                        <th>ESTADO</th>
                </tr>
        </thead>
        <tbody id="datos-detalle">
            <?php
                for ($i=0; $i<count($registros);$i++)
                {
                    if ($registros[$i][6]=="ANULADO")
                    {
                        echo '<tr style="text-decoration:line-through; color:red">';
                            echo '
                                <td align="center">
                                    &nbsp;
                                </td>
                                ';
                    }else{
                        echo '<tr>';
                            echo '
                                <td align="center">
                                    <a href="javascript:void();" onclick = "anular('.$registros[$i][0].')"><i class="fa fa-trash text-orange"></i></a>
                                </td>
                                ';
                    }
                        
                    echo '<td>'.$registros[$i][0].'</td>';
                    echo '<td>'.$registros[$i][1].'</td>';
                    echo '<td>'.$registros[$i][2].'</td>';
                    echo '<td>'.$registros[$i][3].'</td>';
                    echo '<td>'.$registros[$i][4].'</td>';
                    echo '<td>'.$registros[$i][5].'</td>';
                    echo '<td>'.$registros[$i][6].'</td>';
                    
                    echo '</tr>';
                        
                }
            ?>

        </tbody>

    </table>