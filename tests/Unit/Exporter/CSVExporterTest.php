<?php

namespace App\Tests\Unit\Exporter;

use App\Exception\InvalidFileException;
use App\Exporter\CSVExporter;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CSVExporterTest extends TestCase
{
    private string $tempFile;
    private CSVExporter $exporter;

    protected function setUp(): void
    {
        $this->tempFile = 'tests/Unit/Exporter/test_2025.csv';
        $this->exporter = new CSVExporter();
    }

    protected function tearDown(): void
    {
        if (!file_exists($this->tempFile)) {
            return;
        }

        unlink($this->tempFile);
    }

    /**
     * @throws InvalidFileException
     */
    #[Test]
    public function it_should_creates_csv_file_with_correct_content(): void
    {
        $data = [
            [
                'Customer' => 'Kim',
                'Charging Station' => 'E-moped charger',
                'Vehicle' => 'E-moped',
                'kWh Usage p/m' => 15.43,
                'Fee' => 0.51,
                'Payment Date' => '2025-02-03',
            ]
        ];

        $this->exporter->export($data, $this->tempFile);

        $this->assertFileExists($this->tempFile);

        $content = file_get_contents($this->tempFile);
        $lines = explode("\n", trim($content));

        $this->assertEquals(
            'Customer,"Charging Station",Vehicle,"kWh Usage p/m",Fee,"Payment Date"',
            $lines[0]
        );
        $this->assertEquals(
            'Kim,"E-moped charger",E-moped,15.43,0.51,2025-02-03',
            $lines[1]
        );

        $this->assertCount(2, $lines);
    }
}