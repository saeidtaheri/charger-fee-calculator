<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;

class InvalidFileException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not write to the file');
    }
}