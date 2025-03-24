<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class DoctrineCustomerRepository extends ServiceEntityRepository implements CustomerRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * Fetch a dynamic number of customers from the database.
     *
     * @param int $limit The maximum number of customers to fetch.
     * @return Customer[]
     */
    public function getCustomers(int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
