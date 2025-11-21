<?php

namespace App\Repository;

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
    public function findByFilters(?string $statut, ?string $responsable): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($statut) {
            $qb->andWhere('t.statut = :statut')
               ->setParameter('statut', $statut);
        }

        if ($responsable) {
            $qb->andWhere('t.responsable = :responsable')
               ->setParameter('responsable', $responsable);
        }

        return $qb->orderBy('t.dateOuverture', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    public function findDistinctResponsables()
    {
        return $this->createQueryBuilder('t')
                    ->select('DISTINCT t.responsable')
                    ->where('t.responsable IS NOT NULL')
                    ->orderBy('t.responsable', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
    
}
