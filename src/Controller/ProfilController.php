<?php

namespace App\Controller;

use App\Repository\ObjetRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(ObjetRepository $or): Response
    {
        $user = $this->getUser();
        $objets = $or->findAll();
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'objet'=> $objets,
        ]);
    }

    
}
