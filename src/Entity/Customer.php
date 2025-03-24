<?php

namespace App\Entity;

use App\Repository\DoctrineCustomerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineCustomerRepository::class)]
#[ORM\Table(name: 'customers')]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $charging_station = null;

    #[ORM\Column(length: 255)]
    private ?string $vehicle = null;

    #[ORM\Column]
    private ?float $power_consumption_watt = null;

    #[ORM\Column]
    private ?float $hours = null;

    #[ORM\Column]
    private ?int $charging_sessions_per_week = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getChargingStation(): ?string
    {
        return $this->charging_station;
    }

    public function setChargingStation(string $charging_station): static
    {
        $this->charging_station = $charging_station;

        return $this;
    }

    public function getPowerConsumptionWatt(): ?float
    {
        return $this->power_consumption_watt;
    }

    public function setPowerConsumptionWatt(float $power_consumption_watt): static
    {
        $this->power_consumption_watt = $power_consumption_watt;

        return $this;
    }

    public function getVehicle(): ?string
    {
        return $this->vehicle;
    }

    public function setVehicle(string $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getHours(): ?float
    {
        return $this->hours;
    }

    public function setHours(float $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function getChargingSessionsPerWeek(): ?int
    {
        return $this->charging_sessions_per_week;
    }

    public function setChargingSessionsPerWeek(int $charging_sessions_per_week): static
    {
        $this->charging_sessions_per_week = $charging_sessions_per_week;

        return $this;
    }
}
