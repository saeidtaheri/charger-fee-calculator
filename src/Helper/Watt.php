<?php

namespace App\Helper;

final class Watt
{
    private float $watts;

    public function __construct(float $watts) {
        $this->watts = $watts;
    }

    public function toKilowatts(): float {
        return $this->watts / 1000;
    }

    public function toKWH(float $hours): float {
        return $this->watts * $hours / 1000;
    }

    public function getWatts(): float {
        return $this->watts;
    }
}