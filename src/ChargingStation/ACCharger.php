<?php
declare(strict_types=1);

namespace App\ChargingStation;

final class ACCharger implements ChargingStationInterface
{
    const MONTHLY_FEE = 0.021;

    public function calculateFee(float $kWh): float
    {
        return $kWh * self::MONTHLY_FEE;
    }
}