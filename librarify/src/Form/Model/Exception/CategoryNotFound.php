<?php

namespace App\Form\Model\Exception;

class CategoryNotFound extends \Exception
{

    /**
     * @throws CategoryNotFound
     */
    public static function throwException()
    {
        throw new self('Category not found');
    }
}