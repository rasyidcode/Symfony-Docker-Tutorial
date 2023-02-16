<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ProjectController extends AbstractController
{
    #[Route('/project', name: 'project_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $projects = $doctrine
            ->getRepository(Project::class)
            ->findAll();

        $data = [];

        foreach($projects as $project) {
            $data[] = [
                'id'            => $project->getId(),
                'name'          => $project->getName(),
                'description'   => $project->getDescription(),
            ];
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/project', name: 'project_create', methods: ['POST'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $project = new Project();
        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));

        $entityManager->persist($project);
        $entityManager->flush();

        return $this->json('Created new project successfully with id ' . $project->getId(), Response::HTTP_CREATED);
    }

    #[Route('/project/{id}', name: 'project_show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $project = $doctrine
            ->getRepository(Project::class)
            ->find($id);

        if (!$project) {
            return $this->json('No project found for id: '. $id, Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id'    => $project->getId(),
            'name'  => $project->getName(),
            'description' => $project->getDescription()
        ]);
    }

    #[Route('/project/{id}', name: 'project_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $project = $entityManager
            ->getRepository(Project::class)
            ->find($id);

        if (!$project) {
            return $this->json('No project found for id: ' . $id, Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($project);
        $entityManager->flush();

        return $this->json('Deleted a project successfully with id: ' . $id, Response::HTTP_OK);
    }

    #[Route('/project/{id}', name: 'project_update', methods: ['PUT'])]
    public function update(ManagerRegistry $doctrine, Request $request, int $id)
    {
        $entityManager = $doctrine->getManager();
        $project = $entityManager
            ->getRepository(Project::class)
            ->find($id);
        
        if (!$project) {
            return $this->json('No project found for id: ' . $id, Response::HTTP_NOT_FOUND);
        }
        // dd($request->request->get('name'));
        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $entityManager->flush();

        return $this->json([
            'id'    => $project->getId(),
            'name'  => $project->getName(),
            'description' => $project->getDescription()
        ]);
    }
}
