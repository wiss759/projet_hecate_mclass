<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'categories')]
    private Collection $user;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: UserOpenHours::class)]
    private Collection $userOpenHours;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->userOpenHours = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, UserOpenHours>
     */
    public function getUserOpenHours(): Collection
    {
        return $this->userOpenHours;
    }

    public function addUserOpenHour(UserOpenHours $userOpenHour): self
    {
        if (!$this->userOpenHours->contains($userOpenHour)) {
            $this->userOpenHours->add($userOpenHour);
            $userOpenHour->setCategory($this);
        }

        return $this;
    }

    public function removeUserOpenHour(UserOpenHours $userOpenHour): self
    {
        if ($this->userOpenHours->removeElement($userOpenHour)) {
            // set the owning side to null (unless already changed)
            if ($userOpenHour->getCategory() === $this) {
                $userOpenHour->setCategory(null);
            }
        }

        return $this;
    }
}
