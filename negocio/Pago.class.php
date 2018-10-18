<?php
require_once '../datos/conexion.php';
class Pago extends Conexion
{
    private $numeroPago;
    private $numero_prestamo;
    private $dnCliente;
    private $numero_cuota;
    private $fecha_pago;
    private $total_pagado;
    private $saldo_cuota;
    private $detalle;

    /**
     * @return mixed
     */
    public function getNumeroPago()
    {
        return $this->numeroPago;
    }

    /**
     * @param mixed $numeroPago
     */
    public function setNumeroPago($numeroPago)
    {
        $this->numeroPago = $numeroPago;
    }


    /**
     * @return mixed
     */
    public function getDnCliente()
    {
        return $this->dnCliente;
    }

    /**
     * @param mixed $dnCliente
     */
    public function setDnCliente($dnCliente)
    {
        $this->dnCliente = $dnCliente;
    }



    /**
     * @return mixed
     */
    public function getNumeroPrestamo()
    {
        return $this->numero_prestamo;
    }

    /**
     * @param mixed $numero_prestamo
     */
    public function setNumeroPrestamo($numero_prestamo)
    {
        $this->numero_prestamo = $numero_prestamo;
    }

    /**
     * @return mixed
     */
    public function getNumeroCuota()
    {
        return $this->numero_cuota;
    }

    /**
     * @param mixed $numero_cuota
     */
    public function setNumeroCuota($numero_cuota)
    {
        $this->numero_cuota = $numero_cuota;
    }

    /**
     * @return mixed
     */
    public function getFechaPago()
    {
        return $this->fecha_pago;
    }

    /**
     * @param mixed $fecha_pago
     */
    public function setFechaPago($fecha_pago)
    {
        $this->fecha_pago = $fecha_pago;
    }

    /**
     * @return mixed
     */
    public function getTotalpagado()
    {
        return $this->total_pagado;
    }

    /**
     * @param mixed $total_pagado
     */
    public function setTotalpagado($total_pagado)
    {
        $this->total_pagado = $total_pagado;
    }

    /**
     * @return mixed
     */
    public function getSaldoCuota()
    {
        return $this->saldo_cuota;
    }

    /**
     * @param mixed $saldo_cuota
     */
    public function setSaldoCuota($saldo_cuota)
    {
        $this->saldo_cuota = $saldo_cuota;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }

    public function obtenerCuotasPrestamoID($np)
    {

        try
        {
            $sql = "select numero_cuota, fecha_vencimiento_cuota, importe_cuota, saldo_cuota
                    from cuota_prestamo
                    where saldo_cuota>0 and numero_prestamo=:p_np
                    order by numero_cuota";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_np", $np);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    public function obtenerPrestamos($dni)
    {
        try
        {
            $sql = "select numero_prestamo, fecha_prestamo, importe, numero_cuotas from prestamo where dni_cliente=:p_dni order by 1 asc;";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_dni", $dni);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            return $resultado;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }


    public function agregar()
    {
        $this->dblink->beginTransaction();
        try
        {
            $sql = "select numero+1 as nc from correlativo where tabla = 'pago'";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();

            if ($sentencia->rowCount())
            {
                $nuevoCodigo = $resultado["nc"];
                $this->setNumeroPago($nuevoCodigo);

                //Si el total pagado es igual a cero cancelar la transacción
                if ($this->total_pagado<=0)
                {
                    $this->dblink->rollBack();
                    return false;
                }

                $sql = "INSERT INTO pago_prestamo(numero_pago, fecha_pago, total_pagado, estado)
                        VALUES (:p_np, :p_fp, :p_tp, 'E');";

                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_np", $this->numeroPago);
                $sentencia->bindParam(":p_fp", $this->fecha_pago);
                $sentencia->bindParam(":p_tp", $this->total_pagado);
                $sentencia->execute();


                $datosDetalle = json_decode( $this->getDetalle() );
                //print_r($datosDetalle);

                foreach ($datosDetalle as $key => $value)
                {
                    if ($value->importepagado <=0)
                    {
                        break;
                    }
                    $sql = "INSERT INTO pago_prestamo_cuota(numero_pago, numero_prestamo, numero_cuota, importe_pagado)
                    VALUES (:p_np, :p_nprestamo, :p_ncuota, :p_importepagado)";
                    $sentencia = $this->dblink->prepare($sql);
                    $sentencia->bindParam(":p_np", $this->numeroPago );
                    $sentencia->bindParam(":p_nprestamo", $this->numero_prestamo );
                    $sentencia->bindParam(":p_ncuota", $value->numerocuota );
                    $sentencia->bindParam(":p_importepagado", $value->importepagado );
                    $sentencia->execute();

                    /*ACTUALIZAR EL SALDO*/
                    $sql = "update cuota_prestamo
                                    set saldo_cuota = saldo_cuota- :p_ip
                            where
                                    numero_prestamo=:p_np and numero_cuota=:p_ncuota";

                    $sentencia = $this->dblink->prepare($sql);
                    $sentencia->bindParam(":p_ip", $value->importepagado);
                    $sentencia->bindParam(":p_np", $this->numero_prestamo);
                    $sentencia->bindParam(":p_ncuota", $value->numerocuota);
                    $sentencia->execute();
                    /*ACTUALIZAR EL STOCK DE LOS ARTICULO QUE SE ESTA COMPRANDO*/
                }


                $sql = "update correlativo set numero=numero+1 where tabla='pago'";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->execute();

                $this->dblink->commit();

            }

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }

        return true;
    }

    public function listar($fecha1, $fecha2, $tipo) {
        try {
            $sql = "select * from f_listar_pagos(:fecha1, :fecha2, :tipo);";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":fecha1", $fecha1);
            $sentencia->bindParam(":fecha2", $fecha2);
            $sentencia->bindParam(":tipo", $tipo);
            $sentencia->execute();

            $registros = $sentencia->fetchAll();

            return $registros;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function anular()
    {
        $this->dblink->beginTransaction();
        try {
            $sql = "select numero_prestamo, numero_cuota, importe_pagado from pago_prestamo_cuota where numero_pago = :p_nro_pago";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_nro_pago", $this->numeroPago);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($resultado); $i++)
            {
                $sql = "update cuota_prestamo set saldo_cuota = saldo_cuota + :p_importe where numero_prestamo = :p_nro_p and numero_cuota= :p_nro_c";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->bindParam(":p_importe", $resultado[$i]["importe_pagado"]);
                $sentencia->bindParam(":p_nro_p", $resultado[$i]["numero_prestamo"]);
                $sentencia->bindParam(":p_nro_c", $resultado[$i]["numero_cuota"]);
                $sentencia->execute();
            }

            $sql = "update pago_prestamo set estado = 'A' where numero_pago = :p_nro_pago";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_nro_pago", $this->numeroPago);
            $sentencia->execute();

            $this->dblink->commit();

        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }

        return true;
    }
}