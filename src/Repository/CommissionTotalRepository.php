<?php

namespace App\Repository;

use App\Entity\CommissionTotal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommissionTotal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommissionTotal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommissionTotal[]    findAll()
 * @method CommissionTotal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommissionTotalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionTotal::class);
    }

    // Add custom repository methods here
}