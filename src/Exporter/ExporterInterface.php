<?php
declare(strict_types=1);

namespace App\Exporter;

interface ExporterInterface
{
    public function export(array $data, string $filePath);
}