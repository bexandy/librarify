<?php 

namespace App\Form\Model;

use App\Entity\Book; 

/**
 * 
 */
class BookDto 
{
	public ?string $title = null;
    public ?string $description = null;
    public ?int $score = null;
	public ?string $base64Image = null;

    /** @var CategoryDto[]|null  */
	public ?array $categories = [];

	function __construct()
	{
		$this->categories = [];
	}

    public static function createEmpty(): self
    {
        return new self();
    }

	public static function createFromBook(Book $book): self
	{
		$dto = new self();
		$dto->title = $book->getTitle();

		return $dto;
	}

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }



    /**
     * @return string|null
     */
    public function getBase64Image(): ?string
    {
        return $this->base64Image;
    }

    /**
     * @return CategoryDto[]|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

}