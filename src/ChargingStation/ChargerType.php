<?php
declare(strict_types=1);

namespace App\ChargingStation;

enum ChargerType: string
{
     case EmopedCharger  = 'E-moped Charger';
     case ACCharger = 'AC Charger';
     case DCCharger = 'DC Charger';
}
