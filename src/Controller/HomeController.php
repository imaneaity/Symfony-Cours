<?php

namespace App\Controller;

use App\Form\SearchBookType;
use App\DTO\SearchBookCriteria;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_home')]
    public function home(BookRepository $repository): Response
    {
        $books = $repository -> findAllOrderedByPrice();
        return $this->render('home/home.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/rechercher', name: 'app_home_search')]
    public function search(Request $request, BookRepository $repository): Response
    {
        // 1 Création des critères de recherche
        $criteria = new SearchBookCriteria();

        // 2 Création du formulaire
        $form = $this->createForm(SearchBookType::class, $criteria);

        // 3 - On remplie le formulaire avec ce que l'utilisateur a spécifié
        $form->handleRequest($request);

        // 4. Récupérer les livres correspondant à la recherchie
        $books = $repository->findByCriteria($criteria);

        // On affiche la page HTML
        return $this->render('home/search.html.twig', [
            'books' => $books,
            'form' => $form->createView(),
        ]);
    }


}
