<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\EmailService;
use App\Service\RegisterService;
use App\Service\utilsService;

class RegisterController extends AbstractController
{
    private EmailService $emailService;
    private RegisterService $registerService;
    private UserPasswordHasherInterface $hash;

    //injection des dépendances
    public function __construct(
        EmailService $emailService,
        RegisterService $registerService,
        UserPasswordHasherInterface $hash
        )
    {
        $this->emailService = $emailService;
        $this->registerService = $registerService;
        $this->hash = $hash;
    }

    #[Route('/register/add', name: 'app_register_add')]
    public function addUser(): Response
    {
        return new Response("test");
    }

    #[Route('/register/sendemail', name:'app_register_send_email')]
    public function testEmail(): Response 
    {
        setlocale(LC_ALL, 'fr_FR');
        $chaine = 'email envoyé avec success';
        //transliterate
        $body = mb_convert_encoding($chaine, 'ISO-8859-1', 'UTF-8');
        return new Response($this->emailService->sendEmail(
            'mathieumithridate@adrar-formation.com', 
            'test envoi de mail', 
            $body));
    }
}
