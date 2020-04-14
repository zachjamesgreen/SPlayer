<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Tags;
use App\Service\FileUpload;
use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Song;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload",  methods={"GET"})
     */
    public function index()
    {
        return $this->render('upload/form.html');
    }

    /**
     * @Route("/upload", methods={"POST"})
     */

    public function upload(Request $req, Tags $t, FileUpload $fu, EntityManagerInterface $em)
    {
        $test = [];
        $fs = $req->files->get('files');
        foreach ($fs as $k => $v) {
            $tags = $t->process($v->getRealPath());
            $fileName = "{$tags['title']}.{$v->guessExtension()}";
            $test[$k] = $fileName;

            $artist = $em->getRepository(Artist::class)->findOneByName($tags['artist']);
            // echo print_r($artist === null);
            // die;
            if ($artist === null) {
                $artist = new Artist();
                $artist->setName($tags['artist']);
            }



            $album = $em->getRepository(Album::class)->findOneByTitle($tags['album']);
            if ($album == null) {
                $album = new Album();
                $album->setArtist($artist);
                $album->setYear($tags['year']);
                $album->setTitle($tags['album']);
                $album->setGenre($tags['genre']);
                $album->setBand($tags['band']);
                // $em->persist($album);
            }


            $song = new Song();
            $song->setName($tags['title']);
            $song->setFilename($fileName);
            $song->setTrackNumber($tags['trackNumber']);
            $em->persist($song);

            $song->setAlbum($album);
            $song->setArtist($artist);

            $em->flush();


            $fileName = $fu->upload($v, $fileName, $tags);
        }
        return $this->json($test);
    }
}
