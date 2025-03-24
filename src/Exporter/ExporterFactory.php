<?php
declare(strict_types=1);

namespace App\Exporter;

final class ExporterFactory
{
    public static function make(OutputType $type): ExporterInterface
    {
        return match ($type) {
            OutputType::CSV => new CSVExporter(),
        };
    }
}