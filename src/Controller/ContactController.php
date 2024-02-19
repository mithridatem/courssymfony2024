<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailService;

class ContactController extends AbstractController
{
    private MailService $mailService;
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    #[Route('/contact', name: 'contact')]
    public function email(): Response
    {
        $body = $this->render('contact/index.html.twig');
        return new Response($this->mailService->sendEmail('contact@adrardev.fr','symfony',$body));
    }
}
