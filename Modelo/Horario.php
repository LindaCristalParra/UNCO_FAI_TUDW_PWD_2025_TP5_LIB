<?php
class Horario {
    private int $id;
    private int $cancha_id;
    private DateTime $fecha;
    private DateTime $hora;
    private bool $disponible;

    public function __construct($cancha_id, $fecha, $hora, $disponible = true) {
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

    public function __toString(): string
    {
        $cadena = "Horario: id=" . $this->getId() . ", cancha_id=" . $this->getCanchaId() . ", fecha=" . $this->getFecha()->format('Y-m-d') . ", hora=" . $this->getHora()->format('H:i:s') . ", disponible=" . ($this->isDisponible() ? 'sí' : 'no');
        return $cadena;
    }
    
}