<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategorieRepository;
use App\Repository\KnowledgeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class HomeController extends AbstractController
// {
//     #[Route('/home', name: 'app_home')]
//     public function index(): Response
//     {
//         return $this->render('home/index.html.twig', [
//             'controller_name' => 'HomeController',
//         ]);
//     }
// }

{
    #[Route('/', name: 'app_home')]
    public function index(CategorieRepository $categorieRepository, KnowledgeRepository $knowledgeRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'knowledges' => $knowledgeRepository->findBy([], ['id' => 'DESC'], 5),
        ]);
    }
}