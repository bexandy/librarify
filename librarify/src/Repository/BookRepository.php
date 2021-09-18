<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Book $book): Book
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
        return $book;
    }

    /**
     * @throws ORMException
     */
    public function reload(Book $book): Book
    {
        $this->getEntityManager()->refresh($book);
        return $book;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(Book $book)
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }
}
