<?php
class Horario {
    private int $id;
    private int $cancha_id;
    private DateTime $fecha;
    private DateTime $hora;
    private bool $disponible;
    private string $mensajeOperacion;


    public function __construct($cancha_id, $fecha, $hora, $disponible) {
        $this->cancha_id = $cancha_id;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->disponible = $disponible;
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

    public function getFecha(): DateTime
    {
        return $this->fecha;
    }

    public function setFecha(DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getHora(): DateTime
    {
        return $this->hora;
    }

    public function setHora(DateTime $hora): void
    {
        $this->hora = $hora;
    }

    public function isDisponible(): bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): void
    {
        $this->disponible = $disponible;
    }

        public function getMensajeOperacion(): string
    {
        return $this->mensajeOperacion;
    }

    public function setMensajeOperacion($mensaje): void
    {
        $this->mensajeOperacion = $mensaje;
    }

    public function setear($id, $cancha_id, $fecha, $hora, $disponible): void
    {
        $this->setId($id);
        $this->setCanchaId($cancha_id);
        $this->setFecha($fecha);
        $this->setHora($hora);
        $this->setDisponible($disponible);
    }

    public function __toString(): string
    {
        $cadena = "Horario: id=" . $this->getId() . ", cancha_id=" . $this->getCanchaId() . ", fecha=" . $this->getFecha()->format('Y-m-d') . ", hora=" . $this->getHora()->format('H:i:s') . ", disponible=" . ($this->isDisponible() ? 'sí' : 'no');
        return $cadena;
    }

    public function insertar(): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO horarios (cancha_id, fecha, hora, disponible)
                VALUES ('" 
                . $this->getCanchaId() . "','" 
                . $this->getFecha()->format('Y-m-d') 
                . "','" . $this->getHora()->format('H:i:s') 
                . "','" . ($this->isDisponible()) . "')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Horario->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Horario->insertar: " . $base->getError());
        }
        return $resp;
    }


    public function obtener($fecha): mixed
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM horarios WHERE fecha='" . $fecha . "'AND disponible=1'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql) > 0) {
                $row = $base->Registro();
                $this->setear($row['id'], $row['cancha_id'], $row['fecha'], $row['hora'], $row['disponible']);
                $resp = $this;
            }
        } else {
            $this->setMensajeOperacion("Horario->obtener: " . $base->getError());
        }
        return $resp;
    }

    public function modificar(): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE horarios SET cancha_id='" . $this->getCanchaId() . "', fecha='" . $this->getFecha()->format('Y-m-d') . "',
                hora='" . $this->getHora()->format('H:i:s') . "', disponible='" . ($this->isDisponible()) . "' WHERE id='" . $this->getId() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Horario->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Horario->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar($id): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM horarios WHERE id='" . $id . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Horario->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Horario->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($condicion = ""): array
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM horarios";
        if ($condicion != "")
            $sql .= " WHERE " . $condicion;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $a = new Horario("", "", "", 0, true);
                    $a->setear($row['id'], $row['cancha_id'], $row['fecha'], $row['hora'], $row['disponible']);
                    $arreglo[] = $a;
                }
            }
        }
        return $arreglo;
    }

    public function actualizarDisponibilidad($horario_id, $disponible) : bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE horarios SET disponible='" . ($disponible) . "' WHERE id='" . $horario_id . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Horario->actualizarDisponibilidad: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Horario->actualizarDisponibilidad: " . $base->getError());
        }
        return $resp;
    }

       public function obtenerDisponibilidadPorCancha($fecha, $cancha_id): array
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM horarios WHERE fecha='" . $fecha . "' AND cancha_id='" . $cancha_id . "' AND disponible=1";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $a = new Horario("", "", "", 0, true);
                    $a->setear($row['id'], $row['cancha_id'], $row['fecha'], $row['hora'], $row['disponible']);
                    $arreglo[] = $a;
                }
            }
        }
        return $arreglo;
    }
}