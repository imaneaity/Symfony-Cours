<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BasketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted('ROLE_USER')]
class BasketController extends AbstractController
{
    #[Route('/mon-panier/{id}/ajouter', name: 'app_basket_add')]
    public function add(Book $book, BasketRepository $repository): Response
    {


         // recupération de l'utilisateur connécté
        /** @var User $user */
        $user = $this->getUser();


        $basket = $user->getBasket();

         // Ajout du livre dans le panier
         $basket->addBook($book);

        // Enregistrement du panier
        $repository->add($basket, true);


        return $this->redirectToRoute('app_basket_display');
    }




  
    #[Route('/mon-panier', name: 'app_basket_display')]
    public function display(): Response
    {
        return $this->render('basket/display.html.twig');
    }
}
