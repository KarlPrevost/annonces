<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/categorie", name="admin_categorie_")
 * @package App\Controller\Admin
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategorieRepository $categoryRepo)
    {
        return $this->render('admin/categorie/index.html.twig', [
            'categorie' => $categoryRepo->findALl()
        ]);
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function AjoutCategorie(Request $request)
    {
        $categorie = new Categorie;

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_categorie_home');
        }
        
        return $this->render('admin/categorie/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/modify/{id}", name="modify")
     */
    public function ModifyCategorie(Categorie $categorie, Request $request)
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_categorie_home');
        }
        
        return $this->render('admin/categorie/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
