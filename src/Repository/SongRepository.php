<?php

namespace App\Repository;

use App\Entity\Song;
use App\Entity\Artist;
use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    // /**
    //  * @return Song[] Returns an array of Song objects
    //  */

    public function all()
    {
        $em = $this->getEntityManager();
        $songs = [];
        $result = $em->createQuery("SELECT s FROM \App\Entity\Song s")->getResult();
        return $this->createSongsArray($result);
    }

    public function songsByAlbum($id)
    {
        $em = $this->getEntityManager();
        $result = $em->createQuery("SELECT s FROM \App\Entity\Song s WHERE s.album = :id")
        ->setParameter('id', $id)
        ->getResult();

        return $this->createSongsArray($result);
    }

    public function songsByArtist($id)
    {
        $em = $this->getEntityManager();
        $result = $em->createQuery("SELECT s FROM \App\Entity\Song s WHERE s.artist = :id")
        ->setParameter('id', $id)
        ->getResult();

        return $this->createSongsArray($result);
    }

    public function searchName($value)
    {
        $em = $this->getEntityManager();
        $q = $em->createQuery("SELECT s FROM \App\Entity\Song s WHERE LOWER(s.name) LIKE :value")
            ->setParameter('value', strtolower($value).'%');

        return $this->createSongsArray($q->getResult());
    }






    protected function createSongsArray($result)
    {
        $songs = [];
        foreach ($result as $k => $v) {
            $songs[$k]['id'] = $v->getId();
            $songs[$k]['name'] = $v->getName();
            $songs[$k]['track_number'] = $v->getTrackNumber();
            $songs[$k]['filename'] = $v->getFilename();
            $songs[$k]['album'] = [
                'id' => $v->getAlbum()->getId(),
                'title' => $v->getAlbum()->getTitle(),
                'artist' => [$v->getArtist()->getId(),$v->getArtist()->getName()],
                'year' => $v->getAlbum()->getYear(),
                'band' => $v->getAlbum()->getBand(),
                'genre' => $v->getAlbum()->getGenre()
            ];
            $songs[$k]['artist'] = [
                'id' => $v->getArtist()->getId(),
                'name' => $v->getArtist()->getName()
            ];
        }
        return $songs;
    }
}
