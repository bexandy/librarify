<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\Category;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Form\Type\CategoryFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * 
 */
class CategoryFormProcessor
{
	private $bookManager;
	private $categoryManager;
	private $fileUploader;
	private $formFactory;
	
	function __construct(
		CategoryManager $categoryManager,
		FormFactoryInterface $formFactory
	)
	{
		$this->categoryManager = $categoryManager;
		$this->formFactory = $formFactory;
	}

	public function __invoke(Category $category, Request $request): array
	{
		$categoryDto = CategoryDto::createFromCategory($category);

		$form = $this->formFactory->create(CategoryFormType::class, $categoryDto);
		$form->handleRequest($request);
		if (!$form->isSubmitted()) {
			return [null, 'Form is not submitted'];
		}

		if ($form->isValid()) {
            $category->setTitle($categoryDto->title);
			$this->categoryManager->save($category);
			$this->categoryManager->reload($category);
			return [$category, null];
		}

		return [null, $form];
	}
}