<?php

namespace App\Controller;

use App\ArgumentResolver\Body;
use App\ArgumentResolver\QueryParam;
use App\Dto\CreatePostDto;
use App\Dto\UpdatePostDto;
use App\Dto\UpdatePostStatusDto;
use App\Entity\PostFactory;
use App\Exception\PostNotFoundException;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

#[Route('/posts', name: 'posts_')]
class PostController extends AbstractController
{

    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route(path: '', name: 'index', methods: ['GET'])]
    public function index(
        #[QueryParam] string $keyword,
        #[QueryParam] int $offset = 0,
        #[QueryParam] int $limit = 20
    ): JsonResponse {
        $posts = $this->postRepository->findByKeyword($keyword ?: '', $offset, $limit);
        return $this->json($posts);
    }

    #[Route(path: '/{id}', name: 'get', methods: ['GET'])]
    public function getById(Uuid $id): JsonResponse
    {
        $data = $this->postRepository->findOneBy(['id' => $id]);
        if (!$data) {
            return $this->json(['error' => 'Post was not found by id: '.$id], Response::HTTP_NOT_FOUND);
        }

        return $this->json($data);
    }

    #[Route(path: '', name: 'create', methods: ['POST'])]
    public function create(#[Body] CreatePostDto $data): JsonResponse
    {
        $post = PostFactory::create($data->getTitle(), $data->getContent());
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_CREATED, ['Location' => '/posts' . $post->getId()]);
    }

    #[Route(path: '/{id}', name: 'update', methods: ['PUT'])]
    public function update(Uuid $id, #[Body] UpdatePostDto $data): JsonResponse
    {
        $post = $this->postRepository->findOneBy(['id' => $id]);
        if (!$post) {
            throw new PostNotFoundException($id);
        }

        $post->setTitle($data->getTitle());
        $post->setContent($data->getContent());

        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Uuid $id): JsonResponse
    {
        $post = $this->postRepository->findOneBy(['id' => $id]);
        if (!$post) {
            throw new PostNotFoundException($id);
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{id}/status', name: 'update_status', methods: ['PUT'])]
    public function updateStatus(Uuid $id, #[Body] UpdatePostStatusDto $data): JsonResponse
    {
        $post = $this->postRepository->findOneBy(['id' => $id]);
        if(!$post) {
            throw new PostNotFoundException($id);
        }

        $post->setStatus($data->getStatus());

        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
