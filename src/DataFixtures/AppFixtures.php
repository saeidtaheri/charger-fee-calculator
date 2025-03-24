<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $kim = new Customer();
        $kim->setName('Kim')
            ->setChargingStation('E-moped Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(3600)
            ->setHours(2)
            ->setChargingSessionsPerWeek(2);
        $manager->persist($kim);

        $bert = new Customer();
        $bert->setName('Bert')
            ->setChargingStation('DC Charger')
            ->setVehicle('BYD')
            ->setPowerConsumptionWatt(250000)
            ->setHours(0.5)
            ->setChargingSessionsPerWeek(2);
        $manager->persist($bert);

        $julien = new Customer();
        $julien->setName('Julien')
            ->setChargingStation('AC Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(7200)
            ->setHours(6)
            ->setChargingSessionsPerWeek(5);
        $manager->persist($julien);

        $daphne = new Customer();
        $daphne->setName('Daphne')
            ->setChargingStation('AC Charger')
            ->setVehicle('BYD')
            ->setPowerConsumptionWatt(17000)
            ->setHours(7)
            ->setChargingSessionsPerWeek(3);
        $manager->persist($daphne);

        $kevin = new Customer();
        $kevin->setName('Kevin')
            ->setChargingStation('E-moped Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(2800)
            ->setHours(2.6)
            ->setChargingSessionsPerWeek(4);
        $manager->persist($kevin);

        $volcan = new Customer();
        $volcan
            ->setName('Volcan')
            ->setChargingStation('E-moped Charger')
            ->setVehicle('Hyundai')
            ->setPowerConsumptionWatt(2500)
            ->setHours(4)
            ->setChargingSessionsPerWeek(5);
        $manager->persist($volcan);

        $lionel = new Customer();
        $lionel->setName('Lionel')
            ->setChargingStation('AC Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(7200)
            ->setHours(1)
            ->setChargingSessionsPerWeek(3);
        $manager->persist($lionel);

        $manager->flush();
    }
}
