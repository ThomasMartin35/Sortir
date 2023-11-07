<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Excursion;
use App\Form\Model\FilterModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Excursion>
 *
 * @method Excursion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excursion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excursion[]    findAll()
 * @method Excursion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcursionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Excursion::class);
    }

//    /**
//     * @return Excursion[] Returns an array of Excursion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Excursion
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findOneExcursionWithParticipants(int $excursionId): ?Excursion {
        $queryBuilder = $this->createQueryBuilder('e')
            ->addSelect('p')
            ->leftJoin('e.participants', 'p')
            ->where('e.id = :excursionId')
            ->setParameter('excursionId', $excursionId)
            ->getQuery();

        return $queryBuilder->getOneOrNullResult();
    }

    public function findExcursionByFilters( FilterModel $filterModel)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if($filterModel->getSelectedWords()){
            $queryBuilder->andWhere("e.name LIKE :word")->setParameter("word", $filterModel->getSelectedWords());
        }

        if($filterModel->getSelectedCampus()!== null){
            $queryBuilder->andWhere('e.campus = :selectedCampus')->setParameter("selectedCampus", $filterModel->getSelectedCampus());
        }

        if ($filterModel->getSelectedStartDate()){
            $queryBuilder->andWhere('e.startDate >= :selectedStartDate')->setParameter("selectedStartDate", $filterModel->getSelectedStartDate());
        }

        if ($filterModel->getSelectedEndDate()){
            $queryBuilder->andWhere('e.startDate <= :selectedEndDate')->setParameter("selectedEndDate", $filterModel->getSelectedEndDate());
        }

        if ($filterModel->isOrganizer()){
            $queryBuilder->andWhere('e.organizer = :isOrganizer')->setParameter("isOrganizer", true);
        }

        if ($filterModel->isRegistred()){
            $queryBuilder->andWhere('e.participants = :isRegistred')->setParameter("isRegistred", true);
        }

        if ($filterModel->isNotRegistred()){
            $queryBuilder->andWhere('e.participants = :isNotRegistred')->setParameter("isRegistred", false);
        }

        if($filterModel->isFinished()){
            $queryBuilder->andWhere('e.endDate <= :isFinished')->setParameter("isFinished", true);
        }

        return $queryBuilder->getQuery()->getResult();
    }

}

