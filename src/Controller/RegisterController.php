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
class RegisterController extends AbstractController
{
    #[Route('/register/add', name: 'app_register_add')]
    public function addUser(Request $request,UserPasswordHasherInterface $hasher, 
        UserRepository $repo,EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted()AND $form->isValid()){
            //test si l'utilisateur n'existe pas
            if(!$repo->findOneBy(["email"=>$user->getEmail()])){
                $type = "success";
                $msg = "Le compte a été ajouté en BDD";
                $password = $user->getPassword();
                $hash = $hasher->hashPassword($user,$password);
                $user->setPassword($hash);
                //version en une seule étape
                //$user->setPassword($hasher->hashPassword($user,$user->getPassword()));
                $user->setRoles(['ROLE_USER']);
                $em->persist($user);
                $em->flush();
            }
            //test si il existe
            else{
                $type = "danger";
                $msg = "Les informations sont incorrectes";
            }
            $this->addFlash($type,$msg);
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
