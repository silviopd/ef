<?php
require_once '../datos/conexion.php';
class Cliente extends Conexion
{
    private $dniCliente;
    private $nombre;
    private $apellidos;
    private $direccion;
    private $telefono;

    /**
     * @return mixed
     */
    public function getDniCliente()
    {
        return $this->dniCliente;
    }

    /**
     * @param mixed $dniCliente
     */
    public function setDniCliente($dniCliente)
    {
        $this->dniCliente = $dniCliente;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param mixed $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function obtenerClienteDni($dni)
    {
        try
        {
            $sql = "select *,(apellidos||' '||nombres)::varchar as nombrecliente
                    from cliente where dni_cliente=:p_dni";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_dni", $dni);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc)
        {
            throw $exc;
        }

    }

    public function obtenerClienteNombre($nombreCompleto)
    {
        try {
            $sql = "select *,(apellidos||' '||nombres)::varchar as nombrecliente
                    from cliente where upper(apellidos||' '||nombres) like :p_n";
            $sentencia = $this->dblink->prepare($sql);
            $nombreCompleto = '%'.strtoupper($nombreCompleto).'%';
            $sentencia->bindParam(":p_n", $nombreCompleto);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }

    }
}