<?php  

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookRepository;

class LibraryController extends AbstractController
{

	/**
	 * @Route("/books", name="books_get")
	 */
	public function list(Request $request, BookRepository $bookRepository) {

		$books = $bookRepository->findAll();
		$booksAsArray = [];
		foreach ($books as $book) {
			$booksAsArray[] = [
				'id' => $book->getId(),
				'title' => $book->getTitle(),
				'image' => $book->getImage()
			];
		}

		$response = new JsonResponse();
		$response->setData([
			'success' => true,
			'data' => $booksAsArray
		]);
		return $response;
	}

	/**
	 * @Route("/book/create", name="create_book")
	 */
	public function createBook(Request $request, EntityManagerInterface $em)
	{
		$book = Book::create();
		$response = new JsonResponse();
		$title = $request->get('title', null);
		if (empty($title)) {
			$response->setData([
                'success' => false,
                'error' => 'Title cannot be empty',
                'data' => null
            ]);
            return $response;
		}
		$book->setTitle($title);
		$em->persist($book);
		$em->flush();
		
		$response->setData([
			'success' => true,
			'data' => [
				[
					'id' => $book->getId(),
					'title' => $book->getTitle()
				]
			]
		]);
		return $response;

	}
}