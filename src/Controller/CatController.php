<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatController extends AbstractController
{
    #[Route('/categories/{id}', name: 'app_cat_display')]
    public function display(Category $category, BookRepository $repository): Response
    {
        // Récupération des livres de la base de données
        $books = $repository->findAllByCategory($category->getId());

        // Afficher la page d'une catégorie
        return $this->render('cat/display.html.twig', [
            'books' => $books,
            'category' => $category,
        ]);
    }
}
