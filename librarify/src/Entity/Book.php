<?php

namespace App\Entity;

use App\Entity\Book\Score;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Book
{

    private UuidInterface $id;

    private string $title;

    private ?string $description;

    private Score $score;

    private ?string $image;

    /** @var Collection|Category[] */
    private Collection $categories;

    public function __construct(UuidInterface $uuid)
    {
        $this->id = $uuid;
        $this->score = Score::create();
        $this->categories = new ArrayCollection();
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4());
    }

    public function getId(): ?UuidInterface
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

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Book
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Score
     */
    public function getScore(): Score
    {
        return $this->score;
    }

    /**
     * @param Score $score
     * @return Book
     */
    public function setScore(Score $score): self
    {
        $this->score = $score;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function updateCategories(Category ...$categories)
    {
        /** @var Category[]|ArrayCollection $originalCategories */
        $originalCategories = new ArrayCollection();
        foreach ($this->categories as $category) {
            $originalCategories->add($category);
        }

        // remove categories
        foreach ($originalCategories as $originalCategory) {
            if (!\in_array($originalCategory, $categories)) {
                $this->removeCategory($originalCategory);
            }
        }

        // add categories
        foreach ($categories as $newCategory) {
            if (!$originalCategories->contains($newCategory)) {
                $this->addCategory($newCategory);
            }
        }
    }

    public function update(
        string $title,
        ?string $image,
        ?string $description,
        Score $score,
        Category ...$categories
    )
    {
        $this->title = $title;
        $this->image = $image;
        $this->description = $description;
        $this->score = $score;
        $this->updateCategories(...$categories);
    }

    public function __toString()
    {
        return $this->getTitle() ?? 'Libro';
    }
}
