<?php

namespace App\Repository;

use App\Entity\CardList;
use Doctrine\ORM\Query\Expr;
use Symfony\Bridge\Doctrine\RegistryInterface; 
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CardList|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardList|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardList[]    findAll()
 * @method CardList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardList::class);
    }

    public function findByBoard($boardId)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p')
            ->where('p.board = :id')
            ->leftJoin('App\Entity\Card', 'c', Expr\Join::WITH, 'c.card_list = p')
            ->setParameter('id', $boardId);

        return $qb->getQuery()->getResult();
    }
}
