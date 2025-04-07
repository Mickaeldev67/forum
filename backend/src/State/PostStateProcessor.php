<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Psr\Log\LoggerInterface;
use App\Entity\Post;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;

class PostStateProcessor implements ProcessorInterface
{
    private Security $security;
    private LoggerInterface $logger;
    private PersistProcessor $persistProcessor;

    public function __construct(Security $security, LoggerInterface $logger, PersistProcessor $persistProcessor)
    {
        $this->security = $security;
        $this->logger = $logger;
        $this->persistProcessor = $persistProcessor;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Post) {
            $user = $this->security->getUser();
            $this->logger->debug('Utilisateur récupéré : ', ['user' => $user]);

            if ($user && $data->getAuthor() === null) {
                $data->setAuthor($user);
                $this->logger->debug('Auteur assigné au Post', ['author' => $user]);
            }

            // Déléguer la persistance au processeur par défaut
            $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        return $data;
    }
}
