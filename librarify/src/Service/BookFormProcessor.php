<?php

namespace App\Service;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * 
 */
class BookFormProcessor
{
	private BookRepository $bookRepository;
	private CategoryManager $categoryManager;
	private FileUploader $fileUploader;
	private FormFactoryInterface $formFactory;
	
	function __construct(
	    BookRepository $bookRepository,
	    CategoryManager $categoryManager,
	    FileUploader $fileUploader,
	    FormFactoryInterface $formFactory
	)
	{
		$this->bookRepository = $bookRepository;
		$this->categoryManager = $categoryManager;
		$this->fileUploader = $fileUploader;
		$this->formFactory = $formFactory;
	}

	public function __invoke(Book $book, Request $request): array
	{
		$bookDto = BookDto::createFromBook($book);

        /** @var CategoryDto[]|ArrayCollection $originalCategories */
		$originalCategories = new ArrayCollection();

		foreach ($book->getCategories() as $category) {
			$categoryDto = CategoryDto::createFromCategory($category);
			$bookDto->categories[] = $categoryDto;
			$originalCategories->add($categoryDto);
		}

		$form = $this->formFactory->create(BookFormType::class, $bookDto);
		$form->handleRequest($request);
		if (!$form->isSubmitted()) {
			return [null, 'Form is not submitted'];
		}

		if ($form->isValid()) {
			// remove categories
			foreach ($originalCategories as $originalCategoryDto) {
				if (!\in_array($originalCategoryDto, $bookDto->categories)) {
					$category = $this->categoryManager->find($originalCategoryDto->getId());
					$book->removeCategory($category);
				}
			}

			// add categories
			foreach ($bookDto->getCategories() as $newCategoryDto) {
				if (!$originalCategories->contains($newCategoryDto)) {
                    $category = null;
                    if ($newCategoryDto->getId() !== null) {
                        $category = $this->categoryManager->find($newCategoryDto->getId());
                    }
					if (!$category) {
						$category = $this->categoryManager->create();
						$category->setName($newCategoryDto->getName());
						$this->categoryManager->persist($category);
					}
					$book->addCategory($category);
				}
			}
			$book->setTitle($bookDto->title);
			if ($bookDto->base64Image) {
				$fileName = $this->fileUploader->uploadBase64File($bookDto->base64Image);
				$book->setImage($fileName);
			}
			$this->bookRepository->save($book);

			return [$book, null];
		}

		return [null, $form];
	}
}