<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailService;

class ContactController extends AbstractController
{
    private EmailService $emailService;
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    #[Route('/contact', name: 'contact')]
    public function email(): Response
    {
        $body = $this->render('contact/index.html.twig');
        return new Response($this->emailService->testConfig());
    }
}
