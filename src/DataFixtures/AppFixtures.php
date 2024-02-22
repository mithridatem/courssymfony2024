<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Article;
use App\Entity\User;

use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{   
    //attribut pour stocker la classe UserPasswordHasherInterface
    private UserPasswordHasherInterface $passwordHasher;
    //injection de dépendance (UserPasswordHasherInterface) dans le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;    
    }
    public function load(ObjectManager $manager): void
    {
        //liste d'utilisateurs
        $users = [];
        $categories = [];
        //instance de la classe Faker
        $faker = Faker\Factory::create('fr_FR');
        //boucle de création de 10 utilisateurs
        for ($i=0; $i < 50; $i++) { 
            //stocker le mot de passe dans une variable
            $password = $faker->jobTitle();
            //instancier un objet utilisateur
            $user = new User();
            //setter les propriétés de l'objet utilisateur
            $user->setFirstname($faker->firstName('male'|'female'))
                ->setLastname($faker->lastName())
                ->setEmail($faker->email())
                ->setPassword($this->passwordHasher->hashPassword($user,$password))
                ->setRoles(['ROLE_USER'])
                ->setActivated(false);
            //persister l'objet utilisateur
            $manager->persist($user);
            //ajouter l'objet utilisateur dans le tableau
            $users[]= $user;
        }
        //boucle pour créer 30 catégories
        for ($i=0; $i <30 ; $i++) { 
            //instancier un objet catégorie
            $cat = new Category();
            //setter le nom de la catégorie
            $cat->setName($faker->word());
            //persister l'objet catégorie
            $manager->persist($cat);
            //ajouter l'objet catégorie dans le tableau
            $categories[]= $cat;
        }
        //boucle de création de 200 articles
        for ($i=0; $i < 200; $i++) { 
            //instancier un objet article
            $article = new Article();
            //setter les propriétés de l'objet article
            $article->setTitle($faker->word())
                ->setContent($faker->paragraph())
                ->setCreationDate(new \DateTimeImmutable($faker->date('Y-m-d')))
                ->setUser($users[$faker->randomDigit()]);
            //copie de la liste des catégories
            $tab = $categories;
            //boucle de création de 3 catégories par article
            for ($j=0; $j <3 ; $j++) {
                //nombre aléatoire entre 0 et la taille du tableau -1
                $nbr = $faker->numberBetween(0, count($tab)-1);
                //ajouter la catégorie dans l'article
                $article->addCategory($tab[$nbr]);
                //supprimer la catégorie du tableau
                unset($tab[$nbr]);
                //réindexer le tableau
                sort($tab);
            }
            unset($tab);
            //persister l'objet article
            $manager->persist($article);
        }
        //enregistrer les objets en base de données
        $manager->flush();
    }
}
