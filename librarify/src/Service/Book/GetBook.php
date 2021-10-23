<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Form\Model\Exception\BookNotFound;
use App\Repository\BookRepository;
use Ramsey\Uuid\Uuid;

class GetBook
{
    private BookRepository $bookRepository;

    /**
     * @param BookRepository $bookRepository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @throws BookNotFound
     */
    public function __invoke(string $id): ?Book
    {
        $book = $this->bookRepository->find(Uuid::fromString($id));

        if (!$book) {
            BookNotFound::throwException();
        }

        return $book;
    }

}