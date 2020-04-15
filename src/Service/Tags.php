<?php
namespace App\Service;
use \GetId3\GetId3Core as GetId3;
use \App\Service\Converter;

class Tags {
    /**
     * Get instance of GetId3
     */
    protected $getid3;

    /**
     * holder for id3 tags
     */
    public $tags = [];
    public $t = [];

    /**
     * path to file
     */
    public $filePath;
    public $format;

    public function __construct()
    {
        $this->getid3 = new GetID3();
    }

    public function process($filePath)
    {
        ini_set('mbstring.substitute_character', "none");
        $i = $this->getid3->analyze($filePath);
        $this->tags = $i['tags']['id3v2'];
        $this->t['artist'] = $this->getArtist();
        $this->t['album'] = $this->getAlbum();
        $this->t['band'] = $this->getBand();
        $this->t['title'] = $this->getTitle();
        $this->t['trackNumber'] = $this->getTrackNumber();
        $this->t['set'] = $this->getSet();
        $this->t['genre'] = $this->getGenre();
        $this->t['year'] = $this->getYear();
        return $this->t;
    }

    public function getArtist()
    {
        return $this->tags['artist'][0];
    }

    public function getAlbum()
    {
        return $this->tags['album'][0];
    }

    public function getBand()
    {
        if (isset($this->tags['band'])) {
            return $this->tags['band'][0];
        } else {

        }
    }

    public function getTitle()
    {
        return $this->tags['title'][0];
    }

    public function getTrackNumber()
    {
        $tn = $this->tags['track_number'][0];
        $t = explode('/', $tn)[0];
        return $t;

    }

    public function getSet()
    {
        if (isset($this->tags['part_of_a_set'])) {
            return $this->tags['part_of_a_set'][0];
        } else {
            return null;
        }
    }

    public function getGenre()
    {
        if (count($this->tags['genre']) > 1) {
            return implode(', ', $this->tags['genre']);
        } elseif(count($this->tags['genre']) == 1) {
            return [$this->tags['genre'][0]];
        } else {
            return null;
        }
    }

    public function getYear()
    {
        if (isset($this->tags['year'])) {
            return $this->tags['year'][0];
            // try {
            //     $d = new \DateTime($this->tags['year'][0]);
            //     return $d->format('Y');
            // } catch (\Exception $e) {
            //     return null;
            // }
        } else {
            return null;
        }
    }
}
