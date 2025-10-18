<?php

require_once 'Conector/BaseDatos.php';
class Cancha
{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private float $precio_hora;
    private bool $activa;

    private string $mensajeOperacion;


    public function __construct($nombre, $descripcion, $precio_hora, $activa)
    {

        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio_hora = $precio_hora;
        $this->activa = $activa;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getPrecioHora(): float
    {
        return $this->precio_hora;
    }

    public function setPrecioHora($precio_hora): void
    {
        $this->precio_hora = $precio_hora;
    }

    public function getActiva(): bool
    {
        return $this->activa;
    }

    public function setActiva($activa): void
    {
        $this->activa = $activa;
    }

    public function getMensajeOperacion(): string
    {
        return $this->mensajeOperacion;
    }

    public function setMensajeOperacion($mensaje): void
    {
        $this->mensajeOperacion = $mensaje;
    }

    public function setear($id, $nombre, $descripcion, $precio_hora, $activa): void
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setPrecioHora($precio_hora);
        $this->setActiva($activa);
    }

    public function __toString(): string
    {
        $cadena = "Cancha: id=" . $this->getId() . ", 
                    nombre=" . $this->getNombre() . ", 
                    descripcion=" . $this->getDescripcion() . ", 
                    precio_hora=" . $this->getPrecioHora() . ", 
                    activa=" . ($this->getActiva() ? 'sí' : 'no');
        return $cadena;
    }

    public function insertar(): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO canchas (nombre, descripcion, precio_hora, activa)
                VALUES ('" . $this->getNombre() . "','" . $this->getDescripcion() . "','" . $this->getPrecioHora() . "','" . $this->getActiva() . "')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Cancha->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Cancha->insertar: " . $base->getError());
        }
        return $resp;
    }


    public function obtener($nombre): mixed
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM canchas WHERE nombre='" . $nombre . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql) > 0) {
                $row = $base->Registro();
                $this->setear($row['id'], $row['nombre'], $row['descripcion'], $row['precio_hora'], $row['activa']);
                $resp = $this;
            }
        } else {
            $this->setMensajeOperacion("Cancha->obtener: " . $base->getError());
        }
        return $resp;
    }

    public function modificar(): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE canchas SET nombre='" . $this->getNombre() . "', descripcion='" . $this->getDescripcion() . "',
                precio_hora='" . $this->getPrecioHora() . "', activa='" . $this->getActiva() . "' WHERE id='" . $this->getId() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Cancha->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Cancha->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar($id): bool
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM canchas WHERE id='" . $id . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Cancha->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Cancha->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($condicion = ""): array
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM canchas";
        if ($condicion != "")
            $sql .= " WHERE " . $condicion;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $a = new Cancha("", "", "", 0, true);
                    $a->setear($row['id'], $row['nombre'], $row['descripcion'], $row['precio_hora'], $row['activa']);
                    $arreglo[] = $a;
                }
            }
        }
        return $arreglo;
    }

    public function obtenerCanchasActivas(): array{
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM canchas WHERE activa = TRUE ORDER BY nombre";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $a = new Cancha("", "", "", 0, true);
                    $a->setear($row['id'], $row['nombre'], $row['descripcion'], $row['precio_hora'], $row['activa']);
                    $arreglo[] = $a;
                }
            }
        }
        return $arreglo;
    }

    // Verificar si cancha existe y está activa
    public function canchaDisponible($cancha_id): bool {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT id FROM canchas WHERE id = '" . $cancha_id . "' AND activa = TRUE";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql) > 0) {
                $row = $base->Registro();
                $resp = true;
            }
        } else {
            $this->setMensajeOperacion("Cancha->obtener: " . $base->getError());
        }
        return $resp;
    }

    
}