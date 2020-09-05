<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnonceRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AnnonceRepository $annonceRepo)
    {
        return $this->render('main/index.html.twig', [
            'annonce' => $annonceRepo->findBy(['active' => true], ['created_at' => 'desc']),
        ]);
    }
}
