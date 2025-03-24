<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class InMemoryCustomerRepository implements CustomerRepository
{
    private array $customers = [];

    /**
     * Fetch a dynamic number of customers from the database.
     *
     * @param int $limit The maximum number of customers to fetch.
     * @return Customer[]
     */
    public function getCustomers(int $limit = 10): array
    {
        return $this->customers;
    }

    public function setCustomers(array $customers): void
    {
        $this->customers = $customers;
    }
}
