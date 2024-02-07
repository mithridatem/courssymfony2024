<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $em;
    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }

    public function getAllCategory(): array|null
    {
        $data = $this->categoryRepository->findAll();
        if (!$data) {
            $data = null;
        }
        return $data;
    }
    public function getCategoryId($id): Category|array|null
    {
        try {
            $data = $this->categoryRepository->find($id);
            if (!$data) {
                throw new \Exception('Categorie n\'existe pas');
            }
        } catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
        }
        return $data;
    }
    public function getCategoryByName(string $name): Category|null
    {
        return $this->categoryRepository->findOneBy(["name" => $name]);
    }
    public function insertCategory(?Category $category): bool
    {
        if (!$category) {
            return false;
        }
        $this->em->persist($category);
        $this->em->flush();
        return true;
    }
}
