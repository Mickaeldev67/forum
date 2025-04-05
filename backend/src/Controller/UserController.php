<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface; // Utilisation de l'interface
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;
    private JWTTokenManagerInterface $JWTManager; // Utilisation de l'interface
    private UserRepository $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, 
                                EntityManagerInterface $entityManager, 
                                JWTTokenManagerInterface $JWTManager, // Injection du service via l'interface
                                UserRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->JWTManager = $JWTManager;
        $this->userRepository = $userRepository;
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password'], $data['username'])) {
            return new JsonResponse(['error' => 'Missing parameters'], 400);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);

        // 🔐 Assigne le rôle ROLE_USER
        $user->setRoles(['ROLE_USER']);


        // Hachage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        // Sauvegarde de l'utilisateur
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully'], 201);
    }

    #[Route('/api/currentUser', name: 'api_current_user', methods: ['GET'])]
    public function getCurrentUser(Request $request): JsonResponse
    {
        // Symfony gère l'authentification et fournit l'utilisateur via getUser()
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté (token invalide ou manquant)
        if (!$user) {
            throw new AuthenticationException('Non connecté');
        }

        // Retourner le nom d'utilisateur
        return $this->json(['username' => $user->getUserIdentifier()]);
    }
}