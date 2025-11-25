<?php

namespace App\Repository;

use App\Entity\Responsable;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ticket>
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * Retourne les tickets filtrés
     */
    public function findByFilters(?int $statutId, ?int $responsableId): array
    {
        $qb = $this->createQueryBuilder('t')
                   ->leftJoin('t.statut', 's')
                   ->leftJoin('t.responsable', 'r')
                   ->addSelect('s', 'r');

        if ($statutId) {
            $qb->andWhere('s.id = :statut')
               ->setParameter('statut', $statutId);
        }

        if ($responsableId) {
            $qb->andWhere('r.id = :responsable')
               ->setParameter('responsable', $responsableId);
        }

        return $qb->orderBy('t.dateOuverture', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    public function findDistinctResponsables()
    {
        return $this->createQueryBuilder('t')
                    ->leftJoin('t.responsable', 'r')
                    ->addSelect('r')
                    ->where('r IS NOT NULL')
                    ->groupBy('r.id')
                    ->orderBy('r.nom', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
    
}