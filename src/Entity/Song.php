<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SongRepository")
 */
class Song
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $track_number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="songs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $artist;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Album", inversedBy="songs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $album;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $filename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getArtist(): ?int
    {
        return $this->artist;
    }

    public function setArtist($artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): ?int
    {
        return $this->album;
    }

    public function setAlbum($album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getTrackNumber(): ?int
    {
        return $this->track_number;
    }

    public function setTrackNumber(?int $track_number): self
    {
        $this->track_number = $track_number;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
}
