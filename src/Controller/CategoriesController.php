<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategorieType;
use App\Repository\CategoryRepository;
use App\Repository\ObjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(CategoryRepository $cat): Response
    {
        $categorie = $cat->findAll();
        return $this->render('categories/index.html.twig', [
            'categories' => $categorie,
        ]);
    }

    #[Route('/categories/{id}', name: 'app_categories_show')]
    public function show(CategoryRepository $cr, $id): Response
    {
        $oneCat = $cr->find($id);
        $categorie = $cr->findAll();
        return $this->render('categories/show.html.twig', [
            'categorie' => $oneCat,
            'categories' => $categorie,
        ]);
    }

    #[Route('/categories/add', name: 'app_categories_add')]
    public function add(Request $request): Response
    {
        $newcategorie = new Category;
        $form = $this->createForm(CategorieType::class, $newcategorie);
        $form->handleRequest($request);
        return $this->render('categories/add.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('categories/index.html.twig', [
            'title' => 'Location de matériel entre particuliers',
        ]);
    }

    #[Route('/emprunt/{id}', name: 'app_emprunt')]
    public function emprunt(ObjetRepository $or,$id): Response
    {
        $objet = $or->find($id);
        return $this->render('emprunt/index.html.twig', [
            'objet' => $objet,
        ]);
    }

}
