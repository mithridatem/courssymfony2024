<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;

class RegisterService{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository){
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): array|null {
        $users = $this->userRepository->findAll();
        if(!$users){
            $users = null;
        }
        return $users;
    }

    public function getUserById(int $id) : User|null {
        $user = $this->userRepository->find($id);
        if(!$user){
            $user = null;
        }
        return $user;
    }
    public function getUserByEmail(string $email) : User| null {
        $user = $this->userRepository->findOneBy(["email"=>$email]);
        if(!$user){
            $user = null;
        }
        return $user;
    }
    public function insertUser(?User $user) : bool {
        if(!$user){
            return false;
        }
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }
}
