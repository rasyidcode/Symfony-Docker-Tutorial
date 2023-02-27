<?php

namespace App\Controller;

use App\Entity\Superhero;
use App\Repository\SuperheroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SuperheroBySlugController extends AbstractController
{

    public function __construct(
        private readonly SuperheroRepository $superheroRepository
    ) {}

    public function __invoke(string $slug): Superhero
    {
        $superhero = $this->superheroRepository->findOneBy(['slug' => $slug]);

        if (!$superhero) {
            throw $this->createNotFoundException('No superhero found for this slug');
        }
//        dd($superhero);
        return $superhero;
    }
}
