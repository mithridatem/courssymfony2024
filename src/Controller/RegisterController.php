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
use App\Service\UtilsService;
use Exception;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;

class RegisterController extends AbstractController
{
    private EmailService $emailService;
    private RegisterService $registerService;
    private UserPasswordHasherInterface $hash;
    public function __construct(
        EmailService $emailService,
        RegisterService $registerService,
        UserPasswordHasherInterface $hash
    ) {
        $this->emailService = $emailService;
        $this->registerService = $registerService;
        $this->hash = $hash;
    }

    #[Route('/register/add', name: 'app_register_add')]
    public function addUser(Request $request,Recaptcha3Validator $recaptcha3Validator): Response 
    {
        try {
                $user = new User();
            $form = $this->createForm(RegisterType::class, $user);
            $form->handleRequest($request);
            //test le formulaire est submit et valide
            if ($form->isSubmitted() AND $form->isValid()) {
                //test l'utilisateur est un bot
                if ($recaptcha3Validator->getLastResponse()->getScore() < 0.5) {
                    $msg = "L'utilisateur est un bot";
                    $notice = "danger";
                }
                //l'utilisateur n'est pas un bot
                else {
                    //nettoyer les inputs
                    $user->setFirstname(UtilsService::cleanInput($user->getFirstname()));
                    $user->setLastname(UtilsService::cleanInput($user->getLastname()));
                    $user->setEmail(UtilsService::cleanInput($user->getEmail()));
                    $user->setPassword(UtilsService::cleanInput($user->getPassword()));
                    //test si les champs sont remplis
                    if ($form->getData()->getLastname() AND $form->getData()->getFirstname() AND 
                        $form->getData()->getEmail()) {
                        //test si le mail et le password est valide
                        if (UtilsService::testRegex($request->request->all("register")['password']['first'],
                            $this->getParameter('regex_password')) 
                            AND UtilsService::testRegex($form->getData()->getEmail(),$this->getParameter('regex_mail'))) {
                            //test si le compte n'existe pas
                            if(!$this->registerService->getUserByEmail($form->getData()->getEmail())) {
                                //setter les informations
                                $user->setPassword($this->hash->hashPassword($user,$user->getPassword()));
                                $user->setRoles(["ROLE_USER"]);
                                //ajout en bdd
                                if($this->registerService->insertUser($user)) {
                                    $msg = "le compte : ".$user->getEmail()." a été ajouté à la BDD";
                                    $notice = "success";
                                    //envoi du mail
                                    $this->emailService->sendEmail
                                    (
                                        $user->getEmail(),
                                        "activation du compte",
                                        "<p>Veuillez activer le compte</p>"
                                    );
                                }
                                else {
                                    $msg = "Enregistrement impossible";
                                    $notice = "danger";
                                }
                            }
                            //test le compte existe
                            else {
                                $msg = "Les informations d'inscriptions sont incorrectes";
                                $notice = "danger";
                            }
                        }
                        //test les champs ne sont pas valides
                        else {
                            $msg = "Les champs ne sont pas valides";
                            $notice = "warning";
                        }
                    }
                    //test les champs ne sont pas tous remplis
                    else {
                        $msg = "veuillez remplir tous les champs du formulaire";
                        $notice = "warning";
                    }
                }
                $this->addFlash($notice, $msg);
            }
        }
        catch(Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register/sendemail', name: 'app_register_send_email')]
    public function testEmail(): Response
    {
        //setlocale(LC_ALL, 'fr_FR');
        $chaine = 'email envoyé avec success';
        //transliterate
        $body = mb_convert_encoding($chaine, 'ISO-8859-1', 'UTF-8');
        return new Response($this->emailService->sendEmail(
            'mathieumithridate@adrar-formation.com',
            'test envoi de mail',
            $body
        ));
    }
}
