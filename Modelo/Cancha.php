<?php
class Cancha {
    private int $id;
    private string $nombre;
    private string $descripcion;
    private float $precio_hora;
    private bool $activa;


    public function __construct($nombre, $descripcion, $precio_hora, $activa) {
        
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio_hora = $precio_hora;
        $this->activa = $activa;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function getPrecioHora(): float {
        return $this->precio_hora;
    }

    public function setPrecioHora($precio_hora): void {
        $this->precio_hora = $precio_hora;
    }

    public function getActiva(): bool {
        return $this->activa;
    }

    public function setActiva($activa): void {
        $this->activa = $activa;
    }

    public function __toString(): string {
       $cadena =    "Cancha: id=$this->id, 
                    nombre=$this->nombre, 
                    descripcion=$this->descripcion, 
                    precio_hora=$this->precio_hora, 
                    activa=$this->activa";
       return $cadena;
    }
    


}