<?php
require_once 'Conector/BaseDatos.php';

class Reserva
{
    private int $id;
    private int $cancha_id;
    private string $fecha;
    private string $hora;
    private string $cliente_nombre;
    private string $cliente_email;
    private string $cliente_telefono;
    private string $estado;
    private string $fecha_reserva;
    private string $mensajeOperacion;


    public function __construct(
        int $cancha_id,
        string $fecha,
        string $hora,
        string $cliente_nombre,
        string $cliente_email,
        string $cliente_telefono,
        string $estado,
        string $fecha_reserva
    ) {
        $this->cancha_id = $cancha_id;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->cliente_nombre = $cliente_nombre;
        $this->cliente_email = $cliente_email;
        $this->cliente_telefono = $cliente_telefono;
        $this->estado = $estado;
        $this->fecha_reserva = $fecha_reserva;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCanchaId(): int
    {
        return $this->cancha_id;
    }

    public function setCanchaId(int $cancha_id): void
    {
        $this->cancha_id = $cancha_id;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getHora(): string
    {
        return $this->hora;
    }

    public function setHora(string $hora): void
    {
        $this->hora = $hora;
    }

    public function getClienteNombre(): string
    {
        return $this->cliente_nombre;
    }

    public function setClienteNombre(string $cliente_nombre): void
    {
        $this->cliente_nombre = $cliente_nombre;
    }

    public function getClienteEmail(): string
    {
        return $this->cliente_email;
    }

    public function setClienteEmail(string $cliente_email): void
    {
        $this->cliente_email = $cliente_email;
    }

    public function getClienteTelefono(): string
    {
        return $this->cliente_telefono;
    }

    public function setClienteTelefono(string $cliente_telefono): void
    {
        $this->cliente_telefono = $cliente_telefono;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getFechaReserva(): string
    {
        return $this->fecha_reserva;
    }

    public function setFechaReserva(string $fecha_reserva): void
    {
        $this->fecha_reserva = $fecha_reserva;
    }

    public function getMensajeOperacion(): string
    {
        return $this->mensajeOperacion;
    }

    public function setMensajeOperacion($mensaje): void
    {
        $this->mensajeOperacion = $mensaje;
    }

    public function setear(
        int $id,
        int $cancha_id,
        string $fecha,
        string $hora,
        string $cliente_nombre,
        string $cliente_email,
        string $cliente_telefono,
        string $estado,
        string $fecha_reserva
    ): void {
        $this->setId($id);
        $this->setCanchaId($cancha_id);
        $this->setFecha($fecha);
        $this->setHora($hora);
        $this->setClienteNombre($cliente_nombre);
        $this->setClienteEmail($cliente_email);
        $this->setClienteTelefono($cliente_telefono);
        $this->setEstado($estado);
        $this->setFechaReserva($fecha_reserva);
    }

    public function _toString(): string
    {
        $cadena = "Reserva: ID: " . $this->getId()
            . ", Cancha ID: " . $this->getCanchaId()
            . ", Fecha: " . $this->getFecha()
            . ", Hora: " . $this->getHora()
            . ", Cliente Nombre: " . $this->getClienteNombre()
            . ", Cliente Email: " . $this->getClienteEmail()
            . ", Cliente Telefono: " . $this->getClienteTelefono()
            . ", Estado: " . $this->getEstado()
            . ", Fecha Reserva: " . $this->getFechaReserva();
        return $cadena;
    }

    public function insertar(): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO reservas (cancha_id, fecha, hora, cliente_nombre, cliente_email, cliente_telefono, estado, fecha_reserva)
                VALUES ('" . $this->getCanchaId() . "','" . $this->getFecha() . "','" . $this->getHora() . "','" . $this->getClienteNombre() . "','" . $this->getClienteEmail() . "','" . $this->getClienteTelefono() . "','" . $this->getEstado() . "','" . $this->getFechaReserva() . "')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Reserva->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Reserva->insertar: " . $base->getError());
        }
        return $resp;
    }


    public function obtener($id): mixed
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM reservas WHERE id='" . $id . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql) > 0) {
                $row = $base->Registro();
                // Cast explícito para respetar los tipos estrictos del modelo
                $this->setear(
                    (int)$row['id'],
                    (int)$row['cancha_id'],
                    (string)$row['fecha'],
                    (string)$row['hora'],
                    (string)$row['cliente_nombre'],
                    (string)$row['cliente_email'],
                    (string)$row['cliente_telefono'],
                    (string)$row['estado'],
                    (string)$row['fecha_reserva']
                );
                $resp = $this;
            }
        } else {
            $this->setMensajeOperacion("Reserva->obtener: " . $base->getError());
        }
        return $resp;
    }

    public function modificar($id): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE reservas SET cancha_id='" . $this->getCanchaId() . "', fecha='" . $this->getFecha() . "',
                hora='" . $this->getHora() . "', cliente_nombre='" . $this->getClienteNombre() . "', cliente_email='" . $this->getClienteEmail() . "',
                cliente_telefono='" . $this->getClienteTelefono() . "', estado='" . $this->getEstado() . "', fecha_reserva='" . $this->getFechaReserva() . "' WHERE id='" . $id . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Reserva->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Reserva->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar($id): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM reservas WHERE id='" . $id . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Reserva->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Reserva->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($condicion = ""): array
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM reservas";
        if ($condicion != "")
            $sql .= " WHERE " . $condicion;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    // Construcción segura con tipos correctos
                    $a = new Reserva(
                        (int)$row['cancha_id'],
                        (string)$row['fecha'],
                        (string)$row['hora'],
                        (string)$row['cliente_nombre'],
                        (string)$row['cliente_email'],
                        (string)$row['cliente_telefono'],
                        (string)$row['estado'],
                        (string)$row['fecha_reserva']
                    );
                    $a->setId((int)$row['id']);
                    $arreglo[] = $a;
                }
            }
        }
        return $arreglo;
    }

    public function obtenerReservasPorFecha($fecha, $estado = "confirmada"): array
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM reservas WHERE fecha='" . $fecha . "' AND estado='" . $estado . "'";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $a = new Reserva(
                        (int)$row['cancha_id'],
                        (string)$row['fecha'],
                        (string)$row['hora'],
                        (string)$row['cliente_nombre'],
                        (string)$row['cliente_email'],
                        (string)$row['cliente_telefono'],
                        (string)$row['estado'],
                        (string)$row['fecha_reserva']
                    );
                    $a->setId((int)$row['id']);
                    $arreglo[] = $a;
                }
            }
        }
        return $arreglo;
    }

    public function puedeCancelar($codigo_reserva): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT estado FROM reservas WHERE id = '" . $codigo_reserva . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql) > 0) {
                $row = $base->Registro();
                if ($row['estado'] == 'confirmada') {
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("Reserva->puedeCancelar: " . $base->getError());
        }
        return $resp;
    }

    public function cancelarReserva($codigo_reserva): bool
    {
        $resp = false;

        if ($this->puedeCancelar($codigo_reserva)) {
            $base = new BaseDatos();
            $sql = "UPDATE reservas SET estado='cancelada' WHERE id='" . $codigo_reserva . "'";
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion("Reserva->cancelarReserva: " . $base->getError());
                }
            } else {
                $this->setMensajeOperacion("Reserva->cancelarReserva: " . $base->getError());
            }
        }
        return $resp;
    }
}