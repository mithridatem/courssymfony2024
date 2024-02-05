<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategoryType;
use App\Form\ExempleType;
use App\Repository\CategoryRepository;
use App\Service\UtilsService;
use App\Service\CategoryService;
class CategoryController extends AbstractController
{
    #[Route('/category/add', name: 'app_category_add')]
    public function addCategory(Request $request,EntityManagerInterface $em, CategoryRepository $repo): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() AND $form->isValid()){
            if(!$form->getData()->getName()){
                $msg = "Veuillez remplir tous les champs du formulaire";
                $notice = "warning";
            }
            else if($repo->findOneBy(["name"=>$form->getData()->getName()])){
                $msg = "La catégorie existe déja en BDD";
                $notice = "danger";
            }
            else {
                $category->setName(UtilsService::cleanInput($category->getName()));
                $em->persist($category);
                $em->flush();
                $msg = "La catégorie a été ajouté en BDD";
                $notice = "success";
            }
            $this->addFlash($notice,$msg);
        }
        return $this->render('category/index.html.twig', [
          'formulaire' => $form->createView(),
        ]);
    }
    #[Route('/category/exemple', name:'app_category_exemple')]
    public function exemple(Request $request,EntityManagerInterface $em,
    CategoryRepository $repo): Response {
        $category = new Category();
        $form = $this->createForm(ExempleType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($repo->findOneBy(["name"=>$form->getData()->getName()])){
                $type = 'danger';
                $msg = 'La catégorie existe déja en BDD';
            }
            else{
                $type = 'success';
                $msg = 'La categorie à été ajouté en BDD';
                $em->persist($category);
                $em->flush();
            }
            $this->addFlash($type,$msg );
        }
        return $this->render('category/exemple.html.twig',[
            'formulaire' => $form->createView(),
        ]);
    }
    #[Route('category/all', name:'app_category_all')]
    public function showCategories(CategoryService $categoryService): Response {
        $categories = $categoryService->getAllCategory();
        return $this->render('category/show_all_categories.html.twig',[
            'categories' => $categories,
        ]);
    }
}
