<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Entity\Book\Score;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Model\Exception\BookNotFound;
use App\Form\Model\Exception\CategoryNotFound;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Service\Category\CreateCategory;
use App\Service\Category\GetCategory;
use App\Service\FileUploader;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

class BookFormProcessor
{
	private BookRepository $bookRepository;
    private GetBook $getBook;
    private GetCategory $getCategory;
    private CreateCategory $createCategory;
	private FileUploader $fileUploader;
	private FormFactoryInterface $formFactory;
	
	function __construct(
	    BookRepository $bookRepository,
	    GetBook $getBook,
	    GetCategory $getCategory,
	    CreateCategory $createCategory,
	    FileUploader $fileUploader,
	    FormFactoryInterface $formFactory
	)
	{
		$this->bookRepository = $bookRepository;
        $this->getBook = $getBook;
        $this->getCategory = $getCategory;
        $this->createCategory = $createCategory;
		$this->fileUploader = $fileUploader;
		$this->formFactory = $formFactory;
	}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws BookNotFound
     * @throws CategoryNotFound
     */
    public function __invoke(Request $request, ?string $bookId = null): array
	{
        $book = null;
        $bookDto = null;
        if ($bookId === null) {
            $book = Book::create();
            $bookDto = BookDto::createEmpty();
        } else {
            $book = ($this->getBook)($bookId);
            $bookDto = BookDto::createFromBook($book);
            foreach ($book->getCategories() as $category) {
                $bookDto->categories[] = CategoryDto::createFromCategory($category);
            }
        }

		$form = $this->formFactory->create(BookFormType::class, $bookDto);
		$form->handleRequest($request);
		if (!$form->isSubmitted()) {
			return [null, 'Form is not submitted'];
		}
        if (!$form->isValid()) {
            return [null, $form];
        }

        $categories = [];
        foreach ($bookDto->getCategories() as $newCategoryDto) {
            $category = null;
            if ($newCategoryDto->getId() !== null) {
                $category = ($this->getCategory)($newCategoryDto->getId());
            }
            if ($category === null) {
                $category = ($this->createCategory)($newCategoryDto->getName());
            }
            $categories[] = $category;
        }

        $fileName = null;
        if ($bookDto->getBase64Image()) {
            $fileName = $this->fileUploader->uploadBase64File($bookDto->base64Image);
        }

        $book->update(
            $bookDto->getTitle(),
            $fileName,
            $bookDto->getDescription(),
            Score::create($bookDto->getScore()),
            ...$categories
        );
        $this->bookRepository->save($book);

        return [$book, null];

	}
}