<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\Book\DeleteBook;
use App\Service\Book\GetBook;
use App\Service\BookFormProcessor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 
 */
class BooksController extends AbstractFOSRestController
{
	/**
	 * @Rest\Get(path="/books")
	 * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
	 */
	public function getAction(BookRepository $bookRepository)
	{
		return $bookRepository->findAll();
	}

	/**
	 * @Rest\Post(path="/books")
	 * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
	 */
	public function postAction(
	    BookFormProcessor $bookFormProcessor,
	    BookRepository $bookRepository,
	    Request $request
	)
	{
		$book = Book::create();

		[$book, $error] = ($bookFormProcessor)($book, $request);

		$statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
		$data = $book ?? $error;

		$view = View::create($data, $statusCode);	

		return $view;
	}

    /**
     * @Rest\Get(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(
        string $id,
        GetBook $getBook,
        Request $request
    )
    {
        $book = ($getBook)($id);

        if (!$book) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }

        return $book;
    }

	/**
	 * @Rest\Post(path="/books/{id}")
	 * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
	 */
	public function editAction(
	    string $id,
	    BookFormProcessor $bookFormProcessor,
	    GetBook $getBook,
	    Request $request
	)
	{
		$book = ($getBook)($id);

		if (!$book) {
			return View::create('Book not found', Response::HTTP_BAD_REQUEST);		
		}
		
		[$book, $error] = ($bookFormProcessor)($book, $request);

		$statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
		$data = $book ?? $error;

		$view = View::create($data, $statusCode);	

		return $view;
	}


	/**
	 * @Rest\Delete(path="/books/{id}")
	 * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
	 */
	public function deleteAction(
	    string $id,
	    DeleteBook $deleteBook
	)
	{
        try {
            ($deleteBook)($id);
        } catch (\Throwable$t) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }

		return View::create(null, Response::HTTP_NO_CONTENT);
	}

}