<?php

namespace App\Controller;

use App\ArgumentResolver\QueryParam;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/posts', name: 'posts_')]
class PostController extends AbstractController
{

    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        #[QueryParam] string $keyword,
        #[QueryParam] int $offset = 0,
        #[QueryParam] int $limit = 20
    ): JsonResponse {
        $posts = $this->postRepository->findByKeyword($keyword ?: '', $offset, $limit);
        return $this->json($posts);
    }
}
