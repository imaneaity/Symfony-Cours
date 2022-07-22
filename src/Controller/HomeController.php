<?php

namespace App\Controller;

use App\Repository\BookRepository;
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
}
