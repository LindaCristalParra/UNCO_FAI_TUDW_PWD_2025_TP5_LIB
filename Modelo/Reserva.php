<?php
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

}