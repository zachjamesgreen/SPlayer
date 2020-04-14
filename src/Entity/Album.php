<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 */
class Album
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
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true, unique=true)
     */
    private $year;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $genre = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Song", mappedBy="album", orphanRemoval=true)
     */
    private $songs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $artist;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $band;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getGenre(): ?array
    {
        return $this->genre;
    }

    public function setGenre(?array $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getSongs(): ?string
    {
        return $this->songs;
    }

    public function setSongs(Song $songs): self
    {
        $this->songs = $songs;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist($artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs[] = $song;
            $song->setAlbum($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->contains($song)) {
            $this->songs->removeElement($song);
            // set the owning side to null (unless already changed)
            if ($song->getAlbum() === $this) {
                $song->setAlbum(null);
            }
        }

        return $this;
    }

    public function getBand(): ?string
    {
        return $this->band;
    }

    public function setBand(?string $band): self
    {
        $this->band = $band;

        return $this;
    }
}
