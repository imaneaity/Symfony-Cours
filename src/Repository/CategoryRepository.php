<?php

namespace App\Repository;

use App\Entity\Category;
use App\DTO\SearchCategoryCriteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function add(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findByCriteria(SearchCategoryCriteria $criteria): array
    {
        // Création du query builder : l'assistant de requête à la base de données
        $qb = $this->createQueryBuilder('category');

        // On test si une recherche par nom est demandé
        if ($criteria->name) {
            // Si oui, alors on filtre les catégories par leurs nom
            $qb->andWhere('category.name LIKE :name')
                ->setParameter('name', "%$criteria->name%");
        }

        return $qb
            ->orderBy("category.$criteria->orderBy", $criteria->direction)
            ->setMaxResults($criteria->limit)
            ->setFirstResult(($criteria->page - 1) * $criteria->limit)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
