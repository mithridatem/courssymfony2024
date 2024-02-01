<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private EntityManagerInterface $em;
    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em)
    {
        $this->articleRepository = $articleRepository;
        $this->em = $em;
    }

    #[Route('/article/all', name: 'app_article_all')]
    public function showAllArticles(): Response
    {
        $articles = $this->articleRepository->findAll();
        //dd($articles[0]->getUser()->getEmail());
        return $this->render('article/article_all.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route('/article/id/{id}', name: 'app_article_id')]
    public function showArticleById($id): Response
    {
        $article = $this->articleRepository->find($id);
        return $this->render('article/article_id.html.twig', [
            'article' => $article,
        ]);
    }
    #[Route('/article/add', name: 'app_article_add')]

    public function addArticle(
        Request $request,
        UserInterface $userInterface,
        UserRepository $userRepository
    ): Response {
        //instanciation de l'entité Article
        $article = new Article();
        //création du formulaire
        $form = $this->createForm(ArticleType::class, $article);
        //recupération des données
        $form->handleRequest($request);
        //test de soumission et de validité du formulaire
        if ($form->isSubmitted() and $form->isValid()) {
            //test si les champs sont remplis
            if (
                $form->getData()->getTitle() and $form->getData()->getContent()
                and $form->getData()->getCreationDate()
            ) {
                //test si l'article n'existe pas déja
                if (!$this->articleRepository->findOneBy([
                    "title" => $form->getData()->getTitle(),
                    "content" => $form->getData()->getContent()
                ])) {
                    $article->setUser($userRepository->findOneBy(["email" => $userInterface->getUserIdentifier()]));
                    $this->em->persist($article);
                    $this->em->flush();
                    $type = "success";
                    $message = "Article ajouté avec succès";
                }
                //test si il existe déja
                else {
                    $type = "danger";
                    $message = "Cet article existe déja";
                }
            }
            //test si les champs sont vides
            else {
                $type = "warning";
                $message = "Veuillez remplir tous les champs";
            }
            $this->addFlash($type, $message);
        }
        //return de la vue twig
        return $this->render('article/article_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/article/update/{id}', name: 'app_article_update')]
    public function updateArticle($id,Request $request,UserInterface $userInterface): Response {        

        $article = $this->articleRepository->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() AND $form->isValid()){  
            if($form->getData()->getTitle()!="vide" OR $form->getData()->getContent()!="vide"){
                if($article->getUser()->getEmail()==$userInterface->getUserIdentifier()){
                    // test si ce n'est pas un doublon
                    if (!$this->articleRepository->findByDoublon($article->getTitle(),
                    $article->getContent(),$article->getId())) {
                        $this->em->flush();
                        $type = "success";
                        $message = "L'article a été modifié en BDD";
                    }
                    //test si doublon
                    else{
                        $type = "danger";
                        $message = "L'article est déja existant";
                    }
                }
                else{
                    $type = "danger";
                    $message = "Vous n'etes pas autorisé à modifier cet article";
                }
            }
            else{
                $type = "warning";
                $message = "vide";
            }   
            $this->addFlash($type,$message);
        }
        return $this->render('article/article_update.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
