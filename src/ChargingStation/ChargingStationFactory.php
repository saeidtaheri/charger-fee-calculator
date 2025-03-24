<?php
declare(strict_types=1);

namespace App\ChargingStation;

final class ChargingStationFactory
{
    public static function make(ChargerType $type): ChargingStationInterface
    {
        return match ($type) {
            ChargerType::EmopedCharger => new EMopedCharger(),
            ChargerType::ACCharger => new ACCharger(),
            ChargerType::DCCharger => new DCCharger(),
        };
    }
}