<?php
declare(strict_types=1);

namespace App\Service;

use App\ChargingStation\ChargerType;
use App\ChargingStation\ChargingStationFactory;
use App\ChargingStation\ChargingStationInterface;
use App\Entity\Customer;
use App\Helper\Watt;
use App\Repository\CustomerRepository;
use DateTime;

final readonly class ChargerFeeCalculatorService
{
    public function __construct(private CustomerRepository $repository)
    {
    }

    public function getCustomersYearlyChargeFeeFor(int $year): array
    {
        $customers = $this->repository->getCustomers();

        $result = [];
        foreach ($customers as $customer) {
            for ($month = 1; $month <= 12; $month++) {
                $usage = $this->calculateCustomerMonthlyUsageInKWH($customer);
                $monthlyFee = $this->calculateCustomerMonthlyFee($customer, $usage, $month, $year);
                $paymentDate = $this->getCustomerPaymentDate($month, $year);
                $result[] = [
                    'Customer' => $customer->getName(),
                    'Charging Station' => $customer->getChargingStation(),
                    'Vehicle' => $customer->getVehicle(),
                    'kWh Usage p/m' => $usage,
                    'Fee' => round($monthlyFee, 2),
                    'Payment Date' => $paymentDate
                ];
            }
        }

        return $result;
    }

    private function calculateCustomerMonthlyUsageInKWH(Customer $customer): float
    {
        $watt = new Watt($customer->getPowerConsumptionWatt());
        $usageInKWH = $watt->toKWH($customer->getHours());

        return $usageInKWH * $customer->getChargingSessionsPerWeek() * 4;
    }

    private function calculateCustomerMonthlyFee(
        Customer $customer,
        float    $monthlyUsage,
        int      $month,
        int      $year
    ): float
    {
        $usage = $this->getWorkingDaysUsage($monthlyUsage, $month, $year);

        return $this->getChargingStation($customer)
            ->calculateFee($usage);
    }

    private function getWorkingDaysUsage(
        float $monthlyUsage,
        int   $month,
        int   $year
    ): float
    {
        $totalDays = (new DateTime("$year-$month-01"))->format('t');

        $workingDays = 0;
        for ($day = 1; $day <= $totalDays; $day++) {
            $date = new DateTime("$year-$month-$day");
            if ($date->format('N') < 6) {
                $workingDays++;
            }
        }

        return ($monthlyUsage / $totalDays) * $workingDays;
    }

    private function getCustomerPaymentDate(int $month, int $year): string
    {
        $firstMonday = new DateTime("first Monday of $year-$month");

        return $firstMonday->format('Y-m-d');
    }

    private function getChargingStation(Customer $customer): ChargingStationInterface
    {
        return ChargingStationFactory::make(
            ChargerType::from($customer->getChargingStation())
        );
    }
}