<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use App\Service\Category\CategoryFormProcessor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 
 */
class CategoryController extends AbstractFOSRestController
{
	/**
	 * @Rest\Get(path="/categories")
	 * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
	 */
	public function getAction(CategoryRepository $categoryRepository)
	{
		return $categoryRepository->findAll();
	}

    /**
     * @Rest\Post(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        CategoryFormProcessor $categoryFormProcessor
    )
    {
        [$category, $error] = ($categoryFormProcessor)($request);
        $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $category ?? $error;
        return View::create($data, $statusCode);

    }

    /**
     * @Rest\Post(path="/categories/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        CategoryFormProcessor $categoryFormProcessor,
        Request $request
    )
    {
        [$category, $error] = ($categoryFormProcessor)($request, $id);
        $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $category ?? $error;
        return View::create($data, $statusCode);

    }
}