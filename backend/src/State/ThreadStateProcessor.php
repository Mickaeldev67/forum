<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Psr\Log\LoggerInterface;
use App\Entity\Thread;
use Doctrine\ORM\EntityManagerInterface;

class ThreadStateProcessor implements ProcessorInterface
{
    private Security $security;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Thread) {
            $user = $this->security->getUser();
            $this->logger->debug('Utilisateur récupéré : ', ['user' => $user]);

            if ($user && $data->getAuthor() === null) {
                $data->setAuthor($user);
                $this->logger->debug('Auteur assigné au Thread', ['author' => $user]);
                // Persister les modifications en base de données
                $this->entityManager->persist($data);
                $this->entityManager->flush();
            }
        }

        return $data;
    }
}
