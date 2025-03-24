<?php

namespace App\Repository;

interface CustomerRepository
{
    public function getCustomers(int $limit): array;
}