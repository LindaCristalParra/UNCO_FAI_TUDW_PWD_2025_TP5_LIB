<?php
class Reserva {
    private int $id;
    private int $cancha_id;
    private DateTime $fecha;
    private DateTime $hora_inicio;
    private DateTime $hora_fin;
    private string $cliente_nombre;
    private string $cliente_email;
    private string $cliente_telefono;
    private string $estado;
    private float $precio;

    public function __construct($fecha, $hora_inicio, $hora_fin, $cliente_nombre, $cliente_email, $cliente_telefono, $estado, $precio) {
        $this->fecha = $fecha;
        $this->hora_inicio = $hora_inicio;
        $this->hora_fin = $hora_fin;
        $this->cliente_nombre = $cliente_nombre;
        $this->cliente_email = $cliente_email;
        $this->cliente_telefono = $cliente_telefono;
        $this->estado = $estado;
        $this->precio = $precio;
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

    public function getHoraInicio(): DateTime
    {
        return $this->hora_inicio;
    }

    public function setHoraInicio(DateTime $hora_inicio): void
    {
        $this->hora_inicio = $hora_inicio;
    }

    public function getHoraFin(): DateTime
    {
        return $this->hora_fin;
    }

    public function setHoraFin(DateTime $hora_fin): void
    {
        $this->hora_fin = $hora_fin;
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

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function __toString(): string {
       $cadena =    "Reserva: id=" . $this->getId() . ", 
                    cancha_id=" . $this->getCanchaId() . ", 
                    fecha=" . $this->getFecha()->format('Y-m-d') . ", 
                    hora_inicio=" . $this->getHoraInicio()->format('H:i:s') . ", 
                    hora_fin=" . $this->getHoraFin()->format('H:i:s') . ", 
                    cliente_nombre=" . $this->getClienteNombre() . ", 
                    cliente_email=" . $this->getClienteEmail() . ", 
                    cliente_telefono=" . $this->getClienteTelefono() . ", 
                    estado=" . $this->getEstado() . ", 
                    precio=" . $this->getPrecio();
       return $cadena;
    }

}    