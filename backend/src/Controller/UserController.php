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

        // Hachage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        // Sauvegarde de l'utilisateur
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully'], 201);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        // Récupération des données envoyées
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Missing credentials'], 400);
        }

        // Recherche de l'utilisateur par email
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        // Création du token JWT
        $token = $this->JWTManager->create($user);

        // Créer une réponse et ajouter un cookie contenant le JWT
        $response = new JsonResponse(['message' => 'success']);

        // Ajouter le JWT dans un cookie HTTP-only
        $response->headers->setCookie(
            new Cookie(
                'BEARER', // Nom du cookie
                $token,   // Le contenu du token
                time() + 3600, // Expiration du cookie (1 heure)
                '/', // Path
                null, // Domaine
                true, // Secure (vrai en HTTPS)
                true, // HTTPOnly (ne peut pas être accédé par JS)
                false, // Raw (false pour encodage)
                'lax'  // SameSite Policy (lax, strict, none)
            )
        );

        return $response;
    }
}