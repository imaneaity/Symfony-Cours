<?php

namespace App\Repository;

use App\Entity\Book;
use App\DTO\SearchBookCriteria;
use App\Entity\PublishingHouse;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function add(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


 public function findAllOrderedByPrice() :array
 {
    $qb = $this->createQueryBuilder('book');

    return $qb
            ->orderBy('book.price', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
 }


 
 public function findAllByCategory(int $id): array
 {
     $qb = $this->createQueryBuilder('book');

     return $qb
         ->orderBy('book.price', 'DESC')
         ->leftJoin('book.category', 'category')
         ->andWhere('category.id = :id')
         ->setParameter('id', $id)
         ->setMaxResults(20)
         ->getQuery()
         ->getResult();
 }

 public function findByCriteria(SearchBookCriteria $criteria): array
 {
     // Création du query builder
     $qb = $this->createQueryBuilder('book');

     // Filtre les résultat par titre si c'est spécifié
     if ($criteria->title) {
         $qb->andWhere('book.title LIKE :title')
             ->setParameter('title', "%$criteria->title%");
     }

     // Filtre les résultat par auteurs
     if (!empty($criteria->authors)) {
         $qb->leftJoin('book.Author', 'Author')
             ->andWhere('Author.id IN (:authorIds)')
             ->setParameter('authorIds', $criteria->authors);
     }

     // Filtre les résultat par catégories
     if (!empty($criteria->categories)) {
         $qb->leftJoin('book.category', 'category')
             ->andWhere('category.id IN (:categoryIds)')
             ->setParameter('categoryIds', $criteria->categories);
     }

     // Filtre par prix minimum
     if ($criteria->minPrice) {
         $qb->andWhere('book.price >= :minPrice')
             ->setParameter('minPrice', $criteria->minPrice);
     }

     // Filtre par prix maximum
     if ($criteria->maxPrice) {
         $qb->andWhere('book.price <= :maxPrice')
             ->setParameter('maxPrice', $criteria->maxPrice);
     }



      // Filtre par maison d'édition
      if (!empty($criteria->publishingHouses)) {
        $qb
            ->leftJoin('book.publishingHouse', 'PublishingHouse')
            ->andWhere('PublishingHouse.id IN (:publishingHouses)')
            ->setParameter('publishingHouses', $criteria->publishingHouses);
    }

    return $qb
        ->orderBy("book.$criteria->orderBy", $criteria->direction)
        ->setMaxResults($criteria->limit)
        ->setFirstResult(($criteria->page - 1) * $criteria->limit)
        ->getQuery()
        ->getResult();
}

   
 



//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
