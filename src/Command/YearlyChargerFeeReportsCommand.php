<?php

namespace App\Command;

use App\Exporter\ExporterFactory;
use App\Exporter\OutputType;
use App\Service\ChargerFeeCalculatorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class YearlyChargerFeeReportsCommand extends Command
{
    public function __construct(
        private readonly ChargerFeeCalculatorService $chargerFeeCalculatorService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('report:yearly-charger-fee')
            ->setDescription('Generates yearly charger fee reports')
            ->addOption('file', null, InputOption::VALUE_REQUIRED, 'report filename')
            ->addOption('year', null, InputOption::VALUE_OPTIONAL, 'report year');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputYear = $this->getInputYear($input, $output);
        if ($inputYear === -1) {
            return Command::FAILURE;
        }

        $customersYearlyFees = $this->calculateCustomerYearlyFee($inputYear, $output);
        if ($customersYearlyFees === null) {
            return Command::FAILURE;
        }

        $this->generateReport($input, $inputYear, $customersYearlyFees);

        $output->writeln('<info>CSV report successfully generated.');

        return Command::SUCCESS;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    private function getInputYear(InputInterface $input, OutputInterface $output): int
    {
        $inputYear = intval($input->getOption('year') ?? date('Y'));
        if (strlen((string)$inputYear) !== 4) {
            $output->writeln('<error>Error: input year must be 4 characters long.');
            return -1;
        }

        return $inputYear;
    }

    /**
     * @param $inputYear
     * @param OutputInterface $output
     * @return array|null
     */
    private function calculateCustomerYearlyFee($inputYear, OutputInterface $output): ?array
    {
        $customersYearlyFees = $this->chargerFeeCalculatorService
            ->getCustomersYearlyChargeFeeFor($inputYear);

        if (empty($customersYearlyFees)) {
            $output->writeln('<error>Error: There is no report data for generating.');
            return null;
        }

        return $customersYearlyFees;
    }

    /**
     * @param InputInterface $input
     * @param int $inputYear
     * @param array $customersYearlyFees
     * @return void
     */
    private function generateReport(
        InputInterface $input,
        int            $inputYear,
        array          $customersYearlyFees
    ): void
    {
        $filename = $input->getOption('file');
        $filePath = sprintf('%s_%s.csv', $filename, $inputYear);

        ExporterFactory::make(OutputType::CSV)
            ->export($customersYearlyFees, $filePath);
    }
}
