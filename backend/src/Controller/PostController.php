<?php
namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/custom-post', name: 'custom_post', methods: ['POST'])]
    public function customPost(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || !isset($data['content'])) {
            return new JsonResponse(['error' => 'Missing title or content'], 400);
        }

        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);

        $entityManager->persist($post);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Post created'], 201);
    }
}