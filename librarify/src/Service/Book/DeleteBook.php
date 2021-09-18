<?php

namespace App\Service\Book;

use App\Form\Model\Exception\BookNotFound;
use App\Repository\BookRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DeleteBook
{
    private BookRepository $bookRepository;
    private GetBook $getBook;

    /**
     * @param BookRepository $bookRepository
     * @param GetBook $getBook
     */
    public function __construct(BookRepository $bookRepository, GetBook $getBook)
    {
        $this->bookRepository = $bookRepository;
        $this->getBook = $getBook;
    }


    /**
     * @throws OptimisticLockException
     * @throws BookNotFound
     * @throws ORMException
     */
    public function __invoke(string $id)
    {
        $book = ($this->getBook)($id);

        $this->bookRepository->delete($book);
    }
}