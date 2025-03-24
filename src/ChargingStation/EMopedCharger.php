<?php
declare(strict_types=1);

namespace App\ChargingStation;

final class EMopedCharger implements ChargingStationInterface
{
    const DISCOUNT = 0.75;
    const MONTHLY_FEE = 0.033;

    public function calculateFee(float $kWh): float
    {
        $fee = $kWh * self::MONTHLY_FEE;
        if ($this->isEligibleForDiscount($kWh)) {
            $fee *= self::DISCOUNT;
        }

        return $fee;
    }

    private function isEligibleForDiscount(float $kWh): bool
    {
        return $kWh >= 100 && $kWh <= 125;
    }
}