<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function searchName($value)
    {
        $em = $this->getEntityManager();
        $q = $em->createQuery("SELECT a FROM \App\Entity\Album a WHERE LOWER(a.title) LIKE :value")
            ->setParameter('value', strtolower($value).'%');

        return $this->createAlbumsArray($q->getResult());
    }

    public function createAlbumsArray($result)
    {
        $albums = [];
        if (is_array($result)) {
            foreach ($result as $k => $value) {
                $albums[$k]['id'] = $value->getId();
                $albums[$k]['title'] = $value->getTitle();
                $albums[$k]['year'] = $value->getYear();
                $albums[$k]['genre'] = $value->getGenre();
                $albums[$k]['artist'] = $value->getArtist()->getName();
                $albums[$k]['band'] = $value->getBand();
            }
            return $albums;
        } else {
            return [
                'id' => $result->getId(),
                'title' => $result->getTitle(),
                'year' => $result->getYear(),
                'genre' => $result->getGenre(),
                'band' => $result->getBand()
            ];
        }

    }
}
