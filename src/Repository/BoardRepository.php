<?php

namespace App\Repository;

use App\Entity\Board;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Board|null find($id, $lockMode = null, $lockVersion = null)
 * @method Board|null findOneBy(array $criteria, array $orderBy = null)
 * @method Board[]    findAll()
 * @method Board[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Board::class);
    }

    // public function findAuthors($strip)
    // {
    //     $data = [];

    //     $query = $this->getEntityManager()
    //         ->createQuery(
    //             'SELECT c, a 
    //                     FROM App\Entity\CardList c
    //                     JOIN c.board a
    //                     WHERE a.id = :id'
    //         )
    //         ->setParameter('id', $strip);
        
    //     try {
    //         // foreach ($this->_class->fieldNames as $key => $value) {
    //         //     $data[$key] = $value;
    //         // }

    //         $cardLists = $query->getResult();

    //         foreach($cardLists as $cardList) {
    //             $data[] = ['id' => $cardList->getId(), 'title' => $cardList->getTitle()];
    //         }
            
    //         $cardListsIds = array_map(function ($cardList) {
    //             return $cardList->getId();
    //         }, $cardLists);

    //         $query = $this->getEntityManager()
    //             ->createQuery(
    //                 'SELECT c2 
    //                     FROM App:Card c2
    //                     JOIN c2.card_list c
    //                     WHERE c.id IN (:id)'
    //             )
    //             ->setParameter('id', $cardListsIds);

    //         try {
    //             foreach ($query->getResult() as $card) {
    //                 foreach ($data as $key => $cardList) {
    //                     if ($cardList['id'] == $card->getCardList()->getId()) {
    //                         $data[$key]['cards'][] =['id' => $card->getId(), 'title' => $card->getTitle(), 'description' => $card->getDescription()];
    //                     }
    //                 }
    //             }

    //             return $data;
    //         } catch (\Doctrine\ORM\NoResultException $e) {
    //             return null;
    //         }
    //     } catch (\Doctrine\ORM\NoResultException $e) {
    //         return null;
    //     }
    // }
}
