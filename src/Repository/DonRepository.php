<?php

namespace App\Repository;

use App\Entity\Don;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Don>
 *
 * @method Don|null find($id, $lockMode = null, $lockVersion = null)
 * @method Don|null findOneBy(array $criteria, array $orderBy = null)
 * @method Don[]    findAll()
 * @method Don[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Don::class);
    }

    public function save(Don $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Don $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function countByTypeDechet()
    {
        $qb = $this->createQueryBuilder('d')
            ->select('t.NomDechet as type, COUNT(d.id) as count')
            ->leftJoin('d.TypeDechet', 't')
            ->groupBy('t.id');
    
        $results = $qb->getQuery()->getResult();
    
        $counts = [
            'plastique' => 0,
            'alimentaire' => 0,
            'industriel' => 0,
            'ménagers' => 0,
            'dangereux' => 0,
            'de construction' => 0,
            'médical' => 0,
            'vert' => 0
        ];
    
        foreach ($results as $result) {
            $type = $result['type'];
            if ($type) {
                $counts[$type] += $result['count'];
            }
        }
    
        return $counts;
    }
    
    
}
