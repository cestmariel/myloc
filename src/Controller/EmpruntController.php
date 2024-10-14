<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Objet;
use App\Form\EmpruntType;
use App\Repository\CategoryRepository;
use App\Repository\ObjetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmpruntController extends AbstractController
{
    #[Route('/emprunt/{id}', name: 'app_emprunt')]
    public function emprunterObjet(Objet $objet, EntityManagerInterface $entityManager, ObjetRepository $or, Request $request, $id): Response
    {
        $objet = $or->find($id);
        $user = $this->getUser();
        $cat = $objet->getCategorie();
        // Créer un nouvel emprunt
        $emprunt = new Emprunt();
        $emprunt->setObjet($objet);
        $emprunt->setUser($user);
        $emprunt->setDebut(new \DateTime());
        $emprunt->setFin(new \DateTime());
        $form = $this->createForm(EmpruntType::class, $emprunt);
        dump($form);
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour emprunter un objet.');
        }

        $proprietaire = $objet->getUser();
        $pointsATransferer = $cat->getPoints();
        $pointsUser = $user->getPoints();

        if ($pointsUser < $pointsATransferer) {
            $this->addFlash('error', 'Vous n\'avez pas assez de points pour emprunter cet objet.');
            return $this->redirectToRoute('app_profil');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // Transférer les points
        $user->removePoints($pointsATransferer);
        $proprietaire->addPoints($pointsATransferer);

        // Persister les changements
        $emprunt = $form->getData();
        $entityManager->persist($emprunt);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez emprunté l\'objet avec succès !');
        return $this->redirectToRoute('app_profil');
        }

        return $this->render('emprunt/index.html.twig', [
                     'formulaire' => $form,
                     'objet' => $objet,
                     'user' => $user,
                     'category' => $cat,
                 ]);   
    }
}
