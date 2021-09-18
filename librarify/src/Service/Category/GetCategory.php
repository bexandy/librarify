<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Form\Model\Exception\CategoryNotFound;
use App\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;

class GetCategory
{
    private CategoryRepository $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @throws CategoryNotFound
     */
    public function __invoke(string $id): ?Category
    {
        $category = $this->categoryRepository->find(Uuid::fromString($id));
        if (!$category) {
            CategoryNotFound::throwException();
        }
        return $category;
    }


}