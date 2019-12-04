<?php

namespace App\Repository;

use App\Entity\Animal;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Bridge\Doctrine\RegistryInterface;

class AnimalRepository extends ServiceEntityRepository
{
    //public function __construct(ManagerRegistry $registry)
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function findByRaza( $order ){

        $qb = $this->createQueryBuilder('a')
                            ->andWhere("a.raza = :raza")
                            ->setParameter('raza', 'africana')
                            ->orderBy('a.id', 'DESC')
                            ->getQuery();
        
        $resultset = $qb->execute();

        return $resultset;
    }

}
