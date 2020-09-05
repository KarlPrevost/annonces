<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/annonce", name="admin_annonce_")
 * @package App\Controller\Admin
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnonceRepository $annonceRepo)
    {
        return $this->render('admin/annonce/index.html.twig', [
            'annonce' => $annonceRepo->findALl()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Annonce $annonce)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        $this->addFlash('message', 'Ad successfully deleted');

        return $this->redirectToRoute('admin_annonce_home');
    }

    /**
     * @Route("/activate/{id}", name="activate")
     */
    public function activate(Annonce $annonce)
    {
        $annonce->setActive(($annonce->getActive())?false:true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();

        return new Response("true");
    }


}
