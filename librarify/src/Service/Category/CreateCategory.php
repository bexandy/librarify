<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class CreateCategory
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
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function __invoke(string $name): ?Category
    {
        $category = Category::create($name);
        $this->categoryRepository->save($category);
        return $category;
    }


}