<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Service\ChargerFeeCalculatorService;
use App\Tests\Unit\Repository\InMemoryCustomerRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ChargerFeeCalculatorServiceTest extends TestCase
{
    private ChargerFeeCalculatorService $service;
    private CustomerRepository $repositoryMock;

    protected function setUp(): void
    {
        $this->repositoryMock = new InMemoryCustomerRepository();
        $this->service = new ChargerFeeCalculatorService($this->repositoryMock);
    }

    #[Test]
    public function it_should_generate_customers_yearly_charge_fees(): void
    {
        $customer = (new Customer())
            ->setName('Test User')
            ->setChargingStation('E-moped Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(3600)
            ->setHours(2)
            ->setChargingSessionsPerWeek(2);

        $this->repositoryMock->setCustomers([$customer]);

        $result = $this->service->getCustomersYearlyChargeFeeFor(2025);

        $this->assertCount(12, $result);
        $this->assertSame('Test User', $result[0]['Customer']);
        $this->assertSame('Tesla', $result[0]['Vehicle']);
        $this->assertSame('E-moped Charger', $result[0]['Charging Station']);
        $this->assertSame($result[0]['kWh Usage p/m'], 57.6);
        $this->assertSame($result[0]['Fee'], 1.41);
        $this->assertSame($result[0]['Payment Date'], '2025-01-06');
    }
}