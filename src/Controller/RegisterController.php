<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\EmailService;
use App\Service\RegisterService;

class RegisterController extends AbstractController
{
    private EmailService $emailService;
    private RegisterService $registerService;
    public function __construct(EmailService $emailService, RegisterService $registerService){
        $this->emailService = $emailService;
        $this->registerService = $registerService;
    }

    #[Route('/register/add', name: 'app_register_add')]
    public function addUser(): Response
    {
        return new Response("test");
    }
    #[Route('/register/sendemail', name:'app_register_send_email')]
    public function testEmail(): Response {
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
//$objet = iconv("UTF-8", "UTF-8", $maChaine), PHP_EOL;
//$objet = mb_convert_encoding($str, "UTF-8", "UTF-8");