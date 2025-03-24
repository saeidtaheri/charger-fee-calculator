<?php

declare(strict_types=1);

namespace App\Tests\Unit\Command;

use App\Command\YearlyChargerFeeReportsCommand;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Service\ChargerFeeCalculatorService;
use App\Tests\Unit\Repository\InMemoryCustomerRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class YearlyChargerFeeReportsCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private string $file;
    private CustomerRepository $repository;

    protected function setUp(): void
    {
        $this->file = 'tests/Unit/Command/test_2025.csv';

        $this->repository = new InMemoryCustomerRepository();
        $chargerFeeCalculatorService = new ChargerFeeCalculatorService($this->repository);
        $command = new YearlyChargerFeeReportsCommand($chargerFeeCalculatorService);

        $application = new Application();
        $application->add($command);
        $this->commandTester = new CommandTester($command);
    }

    #[Test]
    public function it_should_generate_csv_file_with_one_customer(): void
    {
        $customer = new Customer();
        $customer->setName('kim')
            ->setChargingStation('E-moped Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(3600)
            ->setHours(2)
            ->setChargingSessionsPerWeek(2);

        $this->repository->setCustomers([$customer]);

        $this->commandTester->execute([
            '--year' => 2025,
            '--file' => 'tests/Unit/Command/test'
        ]);

        $this->assertFileExists($this->file);

        $contents = file_get_contents($this->file);

        $this->assertStringContainsString('kim', $contents);
        $this->assertStringContainsString('E-moped Charger', $contents);
        $this->assertStringContainsString('57.6', $contents);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('CSV report successfully generated.', $output);
        $this->assertSame(0, $this->commandTester->getStatusCode());
    }

    #[Test]
    public function it_should_generate_csv_file_with_more_than_one_customer(): void
    {
        $customer1 = new Customer();
        $customer1->setName('kim')
            ->setChargingStation('E-moped Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(3600)
            ->setHours(2)
            ->setChargingSessionsPerWeek(2);

        $customer2 = new Customer();
        $customer2->setName('Bert')
            ->setChargingStation('E-moped Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(3600)
            ->setHours(2)
            ->setChargingSessionsPerWeek(2);

        $this->repository->setCustomers([$customer1, $customer2]);
        $this->commandTester->execute([
            '--year' => 2025,
            '--file' => 'tests/Unit/Command/test'
        ]);
        $file = 'tests/Unit/Command/test_2025.csv';
        $this->assertFileExists($file);

        $contents = file_get_contents($file);

        $this->assertStringContainsString('kim', $contents);
        $this->assertStringContainsString('E-moped Charger', $contents);
        $this->assertStringContainsString('57.6', $contents);

        $this->assertStringContainsString('Bert', $contents);
        $this->assertStringContainsString('E-moped Charger', $contents);
        $this->assertStringContainsString('57.6', $contents);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('CSV report successfully generated.', $output);
        $this->assertSame(0, $this->commandTester->getStatusCode());
    }

    #[Test]
    public function it_should_stop_without_customer_provided(): void
    {
        $this->commandTester->execute([
            '--year' => 2025,
            '--file' => 'tests/Unit/Command/test'
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Error: There is no report data for generating.', $output);
        $this->assertSame(1, $this->commandTester->getStatusCode());
    }

    #[Test]
    public function it_should_generate_file_with_current_year(): void
    {
        $customer = new Customer();
        $customer->setName('kim')
            ->setChargingStation('E-moped Charger')
            ->setVehicle('Tesla')
            ->setPowerConsumptionWatt(3600)
            ->setHours(2)
            ->setChargingSessionsPerWeek(2);

        $this->repository->setCustomers([$customer]);

        $this->commandTester->execute([
            '--file' => 'tests/Unit/Command/test'
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('CSV report successfully generated.', $output);
        $this->assertSame(0, $this->commandTester->getStatusCode());
    }

    #[Test]
    public function it_should_stop_with_validation_error_by_invalid_year(): void
    {
        $invalidYear = 123;

        $this->commandTester->execute([
            '--file' => 'tests/Unit/Command/test',
            '--year' => $invalidYear,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Error: input year must be 4 characters long.', $output);
        $this->assertSame(1, $this->commandTester->getStatusCode());
    }

    #[Test]
    public function it_should_stop_with_invalid_year(): void
    {
        $invalidYear = 'abcd';

        $this->commandTester->execute([
            '--file' => 'tests/Unit/Command/test',
            '--year' => $invalidYear,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Error: input year must be 4 characters long.', $output);
        $this->assertSame(1, $this->commandTester->getStatusCode());
    }

    public function tearDown(): void
    {
        if (!file_exists($this->file)) {
            return;
        }

        unlink($this->file);
    }
}