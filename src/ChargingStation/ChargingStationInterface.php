<?php
declare(strict_types=1);

namespace App\ChargingStation;

interface ChargingStationInterface
{
    public function calculateFee(float $kWh): float;
}