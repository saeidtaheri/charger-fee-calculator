<?php
declare(strict_types=1);

namespace App\ChargingStation;

final class DCCharger implements ChargingStationInterface
{
    const MONTHLY_FEE = 0.072;

    public function calculateFee(float $kWh): float
    {
        return $kWh * self::MONTHLY_FEE;
    }
}