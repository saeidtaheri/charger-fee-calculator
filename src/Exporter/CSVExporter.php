<?php
declare(strict_types=1);

namespace App\Exporter;

use App\Exception\InvalidFileException;

final class CSVExporter implements ExporterInterface
{
    /**
     * @throws InvalidFileException
     */
    public function export(array $data, string $filePath): void
    {
        $reportsDir = dirname($filePath);
        if (!is_dir($reportsDir)) {
            mkdir($reportsDir, 0777, true);
        }

        $file = fopen($filePath, 'w');
        if ($file === false) {
            throw new InvalidFileException();
        }

        fputcsv($file, [
            'Customer', 'Charging Station', 'Vehicle',
            'kWh Usage p/m', 'Fee', 'Payment Date'
        ]);

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }
}