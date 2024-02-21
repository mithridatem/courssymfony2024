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
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;

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
    ) {
        $this->emailService = $emailService;
        $this->registerService = $registerService;
        $this->hash = $hash;
    }

    /**
     * Méthode pour ajouter un compte
     * @param Request Classe pour la récupération des données du formulaire (POST)
     * @param Recaptcha3Validator Classe pour le captcha de Google
     * @return Response redirige vers la connexion ou l'inscription
     */
    #[Route('/register/add', name: 'app_register_add')]
    public function addUser(Request $request, Recaptcha3Validator $recaptcha3Validator): Response
    {
        try {
            $user = new User();
            $form = $this->createForm(RegisterType::class, $user);
            $form->handleRequest($request);
            //test le formulaire est soumis et validé
            if ($form->isSubmitted() and $form->isValid()) {
                $notice = "warning";
                //test du captcha
                if ($recaptcha3Validator->getLastResponse()->getScore() > 0.5) {
                    //nettoyage des inputs
                    $firstname = UtilsService::cleanInput($form->getData()->getFirstname());
                    $lastname = UtilsService::cleanInput($form->getData()->getLastname());
                    $email = UtilsService::cleanInput($form->getData()->getEmail());
                    $password = UtilsService::cleanInput($form->getData()->getPassword());
                    $verifPassword = UtilsService::cleanInput($request->request->all("register")["password"]["second"]);
                    //test si les inputs sont remplis
                    if ($firstname != "" and $lastname != "" and $email != "" and $password != "" and $verifPassword != "") {
                        //test format des données email et password (regex)
                        if (
                            UtilsService::testRegex($email, $this->getParameter('regex_mail')) and
                            UtilsService::testRegex($password, $this->getParameter('regex_password'))
                        ) {
                            //test si les mots de passe correspondent
                            if ($password === $verifPassword) {
                                //test si le compte n'existe pas
                                if (!$this->registerService->getUserByEmail($email)) {
                                    //set des valeurs et hash du mot de passe
                                    $user
                                        ->setFirstname($firstname)
                                        ->setLastname($lastname)
                                        ->setEmail($email)
                                        ->setPassword($this->hash->hashPassword($user, $password))
                                        ->setRoles(['ROLE_USER'])
                                        ->setIsActivated(false);
                                    //enregistrement du compte
                                    if ($this->registerService->insertUser($user)) {
                                        //envoi du mail
                                        $subject = "Activation du compte : " . $lastname . " " . $lastname;
                                        $body = $this->render('email/email.html.twig', [
                                            'url' => "https://localhost:8000/register/activate/" . $user->getId(),
                                        ]);
                                        $this->emailService->sendEmail($email, $subject, $body->getContent());
                                    }
                                    //le compte n'a pas été enregistré
                                    else {
                                        $notice = "danger";
                                        $msg = "Enregistrement impossible";
                                    }
                                }
                                //le compte existe
                                else {
                                    $notice = "danger";
                                    $msg = "Les informations d'inscription \n ne sont pas valides";
                                }
                            }
                            //test les password ne correspondent pas
                            else {
                                $msg = "Les mots de passe ne \n correspondent pas";
                            }
                        }
                        //test le format n'est pas valide
                        else {
                            $msg = "Le mail et ou le mot de \n passe ne sont pas corrects";
                        }
                    }
                    //test les inputs ne sont pas tous remplis
                    else {
                        $msg = "Veuillez remplir tous \n les champs du formulaire";
                    }
                }
                //test l'utilisateur est un bot
                else {
                    $notice = "danger";
                    $msg = "L'utilisateur est un bot";
                }
                //affichage du popup
                $this->addFlash($notice, $msg);
            }
        } catch (\Throwable $th) {
            $this->addFlash("danger", $th->getMessage());
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Méthode qui teste l'envoi de mail
     * 
     * @return Response redirige vers la connexion ou l'inscription
     */
    #[Route('/register/test_email', name: 'app_register_test_email')]
    public function testEmail(): Response
    {
        setlocale(LC_ALL, 'fr_FR');
        $chaine = 'email envoyé avec success';
        //transliterate
        $body = mb_convert_encoding($chaine, 'ISO-8859-1', 'UTF-8');
        return new Response($this->emailService->sendEmail(
            'mathieumithridate@adrar-formation.com',
            'test envoi de mail',
            $body
        ));
    }

    /**
     * Méthode pour activer le compte isActivated => true
     * 
     * @param string $id id du compte à activer
     * @return Response redirige vers la connexion ou l'inscription
     */
    #[Route('/register/activate/{id}', name: 'app_register_activate')]
    public function activateUser(mixed $id): Response
    {
        //test si id est un entier
        if (is_numeric($id)) {
            $user = $this->registerService->getUserById($id);
            //test si le compte existe
            if ($user) {
                $user->setIsActivated(true);
                $this->registerService->updateUser($user);
                //rediriger vers la connexion
                return $this->redirectToRoute('app_login');
            }
            return $this->redirectToRoute('app_register_add');
        }
        //si le compte n'existe pas ou id n'est pas un entier
        return $this->redirectToRoute('app_register_add');
    }
}
