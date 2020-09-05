<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Entity\Image;
use App\Form\AnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\EditProfileType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig');
    }


    /**
     * @Route("/user/annonce/ajout", name="user_annonce_ajout")
     */
    public function ajoutAnnonce(Request $request)
    {
        $annonce = new Annonce;
        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $annonce->setUser($this->getUser());
            $annonce->setActive(false);
            //on recup les images transmises
            $image = $form->get('image')->getData();

            //on boucle sur les images
            foreach($image as $image1){
                // on génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image1->guessExtension();

                // on copie le fichier dans le dossier upload
                $image1->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
            

                // on stock le nom de l'image dans la bdd
                $img = new Image();
                $img->setName($fichier);
                $annonce->addImage($img);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/annonce/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/annonce/edit/{id}", name="user_annonce_edit")
     */
    public function editAnnonce(Annonce $annonce, Request $request)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $annonce->setActive(false);
            // On récupère les images transmises
            $images = $form->get('image')->getData();
                
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Image();
                $img->setName($fichier);
                $annonce->addImage($img);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/annonce/ajout.html.twig', [
            'form' => $form->createView(),
            'annonce' => $annonce
        ]);
    }



    /**
     * @Route("/user/profile/edit", name="user_profile_edit")
     */
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profile updated');

            return $this->redirectToRoute('user');
        }

        return $this->render('user/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/pass/edit", name="user_pass_edit")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if($request->isMethod('POST')){

        
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            
            // Checking password matching
            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'Password successfully changed');
                return $this->redirectToRoute('user');
            }else{
                $this->addFlash('error', 'Password Mismatch');

            }
        }    
        return $this->render('user/editpass.html.twig');
    }

    /**
     * @Route("/supprime/image/{id}", name="annonce_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('image_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Invalid Token'], 400);
        }
    }

}
