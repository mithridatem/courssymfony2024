<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CategoryService;
use App\Entity\Category;

class ApiCategoryController extends AbstractController{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }

    #[Route('/api/cat', name:'app_api_cat_all', methods:'GET')]
    public function getAll(): Response {
        return $this->json($this->categoryService->getAllCategory(),200,[
            'Access-Control-Allow-Origin'=> '*',
            'Content-type'=> 'application/json'
        ],['groups'=>'toutes']);
    }
    #[Route('/api/cat/{id}', name:'app_api_cat_id', methods:'GET')]
    public function getId($id): Response {
        return $this->json($this->categoryService->getCategoryId($id),200,[
            'Access-Control-Allow-Origin'=> '*',
            'Content-type'=> 'application/json'
        ],['groups'=>'toutes']);
    }
}