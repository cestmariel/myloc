<?php

namespace App\Controller;

use App\Entity\Objet;
use App\Form\ObjetType;
use App\Repository\CategoryRepository;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ObjetsController extends AbstractController
{
    #[Route('/objets', name: 'app_objets')]
    public function index(ObjetRepository $or): Response
    {
        $objet = $or->findAll();
        return $this->render('objets/index.html.twig', [
            'objets' => $objet,
        ]);
    }

    #[Route('/objets/add', name: 'app_objets_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $newobjet = new Objet();
        $form = $this->createForm(ObjetType::class, $newobjet);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... gérer l'exception si quelque chose se passe pendant l'upload
                }
    
                $newobjet->setImage($newFilename);
            }
    
            // Récupérer l'utilisateur connecté
            $user = $this->getUser();
            
            if ($user) {
                $newobjet->setUser($user);
                $entityManager->persist($newobjet);
                $entityManager->flush();
    
                $this->addFlash('success', 'L\'objet a été ajouté avec succès.');
                return $this->redirectToRoute('app_objets');
            } else {
                $this->addFlash('error', 'Vous devez être connecté pour ajouter un objet.');
                return $this->redirectToRoute('app_login');
            }
        }
    
        return $this->render('objets/add.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

    // #[Route('/objets/{id}', name: 'app_objets_show')]
    // public function show(ObjetRepository $or,CategoryRepository $cat, $id): Response
    // {
    //     $objet = $or->find($id);
    //     $categorie = $cat->find($objet->getCategorie());
    //     return $this->render('objets/show.html.twig', [
    //         'objet' => $objet,
    //         'categorie'=> $categorie
    //     ]);
    // }

    #[Route('/objets/{id}', name: 'app_objets_show')]
    public function show(ObjetRepository $or, CategoryRepository $cat, $id): Response
    {
        $objet = $or->find($id);
        if (!$objet) {
            throw $this->createNotFoundException('L\'objet demandé n\'existe pas.');
        }
        $categorie = $cat->find($objet->getCategorie());
        return $this->render('objets/show.html.twig', [
            'objet' => $objet,
            'categorie'=> $categorie
        ]);
    }

    #[Route('/objets/modif/{id}', name: 'app_objets_modif')]
    public function modif(Request $request, Objet $objet, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // Vérifier si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Vérifier si l'utilisateur connecté est le propriétaire de l'objet
        if ($this->getUser() !== $objet->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cet objet.');
        }

        $form = $this->createForm(ObjetType::class, $objet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception si quelque chose se passe pendant l'upload
                }

                // Supprimer l'ancienne image si elle existe
                if ($objet->getImage()) {
                    $oldImagePath = $this->getParameter('images_directory').'/'.$objet->getImage();
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $objet->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'L\'objet a été modifié avec succès.');
            return $this->redirectToRoute('app_objets_show', ['id' => $objet->getId()]);
        }

        return $this->render('objets/modif.html.twig', [
            'objet' => $objet,
            'formulaire' => $form->createView(),
        ]);
    }

    #[Route('/objets/supprimer/{id}', name: 'app_objets_supprimer')]
    public function supprimer(Request $request, Objet $objet, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Vérifier si l'utilisateur connecté est le propriétaire de l'objet
        if ($this->getUser() !== $objet->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet objet.');
        }

        // Vérifier le token CSRF pour la sécurité
        if ($this->isCsrfTokenValid('delete'.$objet->getId(), $request->request->get('_token'))) {
            // Supprimer l'image associée si elle existe
            if ($objet->getImage()) {
                $imagePath = $this->getParameter('images_directory').'/'.$objet->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Supprimer l'objet de la base de données
            $entityManager->remove($objet);
            $entityManager->flush();

            $this->addFlash('success', 'L\'objet a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_profil');
    }
}
