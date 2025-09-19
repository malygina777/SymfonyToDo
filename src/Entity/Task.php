<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'task')]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $title = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $position = 0;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dueAt = null;

    #[ORM\ManyToOne(targetEntity: AppUser::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?AppUser $owner = null;

    public function getOwner(): ?AppUser
    {
        return $this->owner;
    }

    public function setOwner(?AppUser $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function getDueAt(): ?\DateTimeImmutable { return $this->dueAt; }
    public function setDueAt(?\DateTimeImmutable $d): self { $this->dueAt = $d; return $this; }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;
        return $this;
    }
    
}
