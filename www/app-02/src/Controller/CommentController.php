<?php

namespace App\Controller;

use App\Dto\CommentWithPostSummaryDto;
use App\Dto\PostSummaryDto;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('comments', name: 'comments')]
class CommentController extends AbstractController
{

    public function __construct(private readonly CommentRepository $commentRepository)
    {
    }

    #[Route('', name: 'welcome')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CommentController.php',
        ]);
    }

    #[Route('/{id}', name: 'getById', methods: ['GET'])]
    public function getById(Uuid $id): JsonResponse
    {
        $comment = $this->commentRepository->findOneBy(['id' => $id]);
        if (!$comment) {
            return $this->json(['error' => 'Comment was not found: ' . $id], Response::HTTP_NOT_FOUND);
        }

        $dto = CommentWithPostSummaryDto::of($comment->getId(), $comment->getContent(), PostSummaryDto::of($comment->getPost()?->getId(), $comment->getPost()?->getTitle(), $comment->getPost()?->getContent(), $comment->getPost()?->getStatus()));
        return $this->json($dto);
    }


}
