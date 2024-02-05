<?php
namespace App\Service;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $em;
    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $em){
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }

    public function getAllCategory(): array|null {
        $data = $this->categoryRepository->findAll();
        if(!$data){
           $data = null;
        }
        return $data;
    }
}