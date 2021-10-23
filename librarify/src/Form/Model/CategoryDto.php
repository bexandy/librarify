<?php 

namespace App\Form\Model;

use App\Entity\Category;
use Ramsey\Uuid\UuidInterface;

/**
 * 
 */
class CategoryDto 
{
	public ?UuidInterface $id = null;
	public ?string $name = null;

	public static function createFromCategory(Category $category): self
	{
		$dto = new self();
		$dto->id = $category->getId();
		$dto->name = $category->getName();

		return $dto;
	}

    /**
     * @return UuidInterface|null
     */
    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

}