<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findAll()
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artist::class);
    }

    public function findOrCreateBy($field,$value)
    {
        $t = $this->createQueryBuilder('a')
            ->andWhere(':field = :val')
            ->setParameters(array('val' => $value, 'field' => $field))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if ($t) {
            return $t;
        } else {
            return new Artist();
        }
    }

    public function searchName($value)
    {
        $em = $this->getEntityManager();
        $q = $em->createQuery("SELECT a FROM \App\Entity\Artist a WHERE LOWER(a.name) LIKE :value")
            ->setParameter('value', strtolower($value).'%');

        return $this->createArtistsArray($q->getResult());
    }

    public function createArtistsArray($result)
    {
        $artists = [];
        if (is_array($result)) {
            foreach ($result as $k => $value) {
                $artists[$k]['id'] = $value->getId();
                $artists[$k]['name'] = $value->getName();
            }
            return $artists;
        } else {
            return [
                'id' => $result->getId(),
                'name' => $result->getName(),
            ];
        }

    }
}
