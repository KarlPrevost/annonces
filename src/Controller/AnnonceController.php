<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Annonce;

/**
 * @Route("/annonce", name="annonce_")
 * @package App\Controller
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/detail/{slug}", name="detail")
     */
    public function detail($slug, AnnonceRepository $annonceRepo)
    {
        $annonce = $annonceRepo->findOneBy(['slug' => $slug]);

        if(!$annonce){
            throw new NotFoundHttpException('No ads found');
        }
        return $this->render('annonce/detail.html.twig', compact('annonce'));
    }

    /**
     * @Route("/favorite/add/{id}", name="add_favorite")
     */
    public function addFavorite(Annonce $annonce)
    {
        if(!$annonce){
            throw new NotFoundHttpException('No ads found');
        }
        $annonce->addFavorite($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/favorite/remove/{id}", name="remove_favorite")
     */
    public function removeFavorite(Annonce $annonce)
    {
        if(!$annonce){
            throw new NotFoundHttpException('No ads found');
        }
        $annonce->removeFavorite($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }
}
