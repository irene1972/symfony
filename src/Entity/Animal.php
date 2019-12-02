<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Animal
{

    private $id;
    private $tipo = 'NULL';
    private $color = 'NULL';
    private $raza = 'NULL';
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getRaza(): ?string
    {
        return $this->raza;
    }

    public function setRaza(?string $raza): self
    {
        $this->raza = $raza;

        return $this;
    }


}
