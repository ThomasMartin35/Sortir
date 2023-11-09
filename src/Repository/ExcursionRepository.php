<?php

namespace App\Repository;

use App\Entity\Excursion;
use App\Entity\State;
use App\Form\Model\FilterModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;


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
    public function __construct(ManagerRegistry $registry, private StateRepository $stateRepository, private Security $security)
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

    public function findAllExcursionsWithoutArchived(): ?array
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin('e.state', 's')
            ->andWhere('s.caption != :archivedState')
            ->setParameter('archivedState', 'Archived')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findAllExcursionsWithoutCreated(): ?array
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin('e.state', 's')
            ->andWhere('s.caption != :createdState')
            ->setParameter('createdState', 'Created')
            ->getQuery();

        return $queryBuilder->getResult();
    }


    public function findExcursionByFilters( FilterModel $filterModel )
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $member = $this->security->getUser();

        if($filterModel->getSelectedWords()){
            $queryBuilder
                ->andWhere("e.name LIKE :word")
                ->setParameter("word", '%'. $filterModel->getSelectedWords().'%');
        }

        if($filterModel->getSelectedCampus()!== null){
            $queryBuilder
                ->andWhere('e.campus = :selectedCampus')
                ->setParameter("selectedCampus", $filterModel->getSelectedCampus());
        }

        if ($filterModel->getSelectedStartDate()){
            $queryBuilder
                ->andWhere('e.startDate >= :selectedStartDate')
                ->setParameter("selectedStartDate", $filterModel->getSelectedStartDate());
        }

        if ($filterModel->getSelectedEndDate()){
            $queryBuilder
                ->andWhere('e.startDate <= :selectedEndDate')
                ->setParameter("selectedEndDate", $filterModel->getSelectedEndDate());
        }

        if ($filterModel->isOrganizer()) {
            $queryBuilder
                ->andWhere('e.organizer = :organizer')
                ->setParameter('organizer', $member);
        }

        if ($filterModel->isRegistred()) {
            $queryBuilder
                ->andWhere(':member MEMBER OF e.participants')
                ->setParameter('member', $member);
        }

        if ($filterModel->isNotRegistred()) {
            $queryBuilder
                ->andWhere(':member NOT MEMBER OF e.participants')
                ->setParameter('member', $member);
        }

        $finishedState = $this->stateRepository->findOneBy(['caption' => 'Finished']);
        if($filterModel->isFinished()){
            $queryBuilder
                ->andWhere('e.state = :finishedState')
                ->setParameter("finishedState", $finishedState);
        }

        return $queryBuilder->getQuery()->getResult();
    }

}

