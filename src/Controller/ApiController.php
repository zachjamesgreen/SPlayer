<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Song;
use App\Entity\Album;
use App\Entity\Artist;
use Doctrine\ORM\Query\ResultSetMappingBuilder;


/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/songs", name="songs")
     */
    public function songs(Request $req, EntityManagerInterface $em)
    {
        $songs = $em->getRepository(Song::class)->all();

        return $this->json($songs);
    }

    /**
     * @Route("/songs/album/{id}")
     */
    public function songsByAlbum($id, EntityManagerInterface $em)
    {
        $songs = $em->getRepository(Song::class)->songsByAlbum($id);

        return $this->json($songs);
    }

    /**
     * @Route("/songs/artist/{id}")
     */
    public function songsByArtist($id, EntityManagerInterface $em)
    {
        $songs = $em->getRepository(Song::class)->songsByArtist($id);

        return $this->json($songs);
    }

    /**
     * @Route("/album/{id}")
     */
    public function album($id, EntityManagerInterface $em)
    {
        $album = $em->getRepository(Album::class)->find($id);

        return $this->json([
            'title' => $album->getTitle(),
            'year' => $album->getYear(),
            'genre' => $album->getGenre(),
            'band' => $album->getBand()
        ]);
    }

    /**
     * @Route("/albums")
     */
    public function albums(EntityManagerInterface $em)
    {
        $albums = $em->getRepository(Album::class)->findAll();
        $a = [];
        foreach ($albums as $key => $value) {
            array_push($a,[
                'title' => $value->getTitle(),
                'year' => $value->getYear(),
                'genre' => $value->getGenre(),
                'band' => $value->getBand()
            ]);

        }

        return $this->json($a);
    }

    /**
     * @Route("/albums/{artist_id}")
     */
     public function albumsByArtist($artist_id, EntityManagerInterface $em)
     {
         $artist = $em->getRepository(Artist::class)->find($artist_id);
         $albums = $em->getRepository(Album::class)->findByArtist($artist);

         $a = [];
         foreach ($albums as $key => $value) {
             array_push($a,[
                 'title' => $value->getTitle(),
                 'year' => $value->getYear(),
                 'genre' => $value->getGenre(),
                 'band' => $value->getBand()
             ]);

         }

         return $this->json($a);
     }
    /**
     * @Route("/artists")
     */
    public function artists(EntityManagerInterface $em)
    {
        $artists = $em->getRepository(Artist::class)->findAll();

        $a = [];
        foreach ($artists as $key => $value) {
            array_push($a,[
                'id' => $value->getId(),
                'name' => $value->getName(),
            ]);

        }

        return $this->json($a);
    }
    /**
     * @Route("/artist/{id}")
     */
    public function artist($id, EntityManagerInterface $em)
    {
        $artist = $em->getRepository(Artist::class)->find($id);

        return $this->json([
            'id' => $artist->getId(),
            'name' => $artist->getName(),
        ]);
    }
    /**
     * @Route("/search")
     */
    public function search(Request $req, EntityManagerInterface $em)
    {
        $q = $req->query->get('q');
        $a = [];
        $al = [];
        $artists = $em->getRepository(Artist::class)->searchName($q);
        $albums = $em->getRepository(Album::class)->searchName($q);
        $songs = $em->getRepository(Song::class)->searchName($q);
        foreach ($artists as $key => $value) {
            array_push($a, [
                'id' => $value->getId(),
                'name' => $value->getName()
            ]);
        }
        foreach ($albums as $key => $value) {
            array_push($al, [
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'year' => $value->getYear(),
                'genre' => $value->getGenre(),
                'band' => $value->getBand()
            ]);
        }

        return $this->json([$a,$al,$songs]);
    }

}
