<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findHidrateAll()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }

    /**
     * @param int $team
     * @return Player[] Returns an array of Player objects
     */
    public function findByTeam(int $team)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.team = :val')
            ->setParameter('val', $team)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int $location
     * @return Player[] Returns an array of Player objects
     */
    public function findByLocation(int $location)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.location = :val')
            ->setParameter('val', $location)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int $location
     * @param int $team
     * @return Player[] Returns an array of Player objects
     */
    public function findByTeamAndLocation(int $team, int $location)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.location = :loc')
            ->andWhere('p.team = :team')
            ->setParameter('loc', $location)
            ->setParameter('team', $team)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Player
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
