<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository) 
    {
        $this->articleRepository = $articleRepository;
    }
    
    #[Route('/article/all', name: 'app_article_all')]
    public function showAllArticles(): Response {
        $articles = $this->articleRepository->findAll();    
        return $this->render('article/index.html.twig',[
            'articles' => $articles,
        ]);
    }
    #[Route('/article/id/{id}', name: 'app_article_id')]
    public function showArticleById($id): Response 
    {
        $article = $this->articleRepository->find($id);
        return $this->render('article/article_id.html.twig',[
            'article' => $article,
        ]);
    }
}
