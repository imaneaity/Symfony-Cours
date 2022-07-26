<?php

namespace App\Controller;

use App\DTO\Payment;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Order;
use App\Form\PaymentType;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\BasketRepository;
use Symfony\Component\HttpFoundation\Request;
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



    #[Route('/mon-panier/{id}/supprimer', name: 'app_basket_remove')]
    public function remove(Book $book, BasketRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // On récupére le panier de l'utilisateur connécté
        $basket = $user->getBasket();

        // Suppression du livre du panier
        $basket->removeBook($book);

        // On enregistre la panier
        $repository->add($basket, true);

        // On redirige vers la page d'affichage du panier
        return $this->redirectToRoute('app_basket_display');
    }

    #[Route('/mon-panier/validation', name: 'app_basket_validate')]
    public function validate(Request $request, OrderRepository $orderRepository, UserRepository $userRepository, BasketRepository $basketRepository): Response
    {
        // création du paimeent
        $payment = new Payment();

        // Récupération de l'utilisateur connécté
        /** @var User $user */
        $user = $this->getUser();
        $payment->address = $user->getDeliveryAdress();

        // création du formulaire
        $form = $this->createForm(PaymentType::class, $payment);

        // On remplie le formulaire avec les données de la requête
        $form->handleRequest($request);

        // On test si le formulaire a bien était envoyé
        if ($form->isSubmitted() && $form->isValid()) {
            // On créer la commande
            $order = new Order();

            // On attache l'utilisateur à la commande
            $order->setUser($user);

            // On boucle sur tout les livres du panier
            foreach ($user->getBasket()->getBooks() as $book) {
                // On ajoute le livre a la commande
                $order->addBook($book);
                // On supprime le livre du panier
                $user->getBasket()->removeBook($book);
            }

            // On ajout l'address de livraison à l'utilisateur
            $user->setDeliveryAdress($payment->address);

            // On sauvegarde le tout !
            $userRepository->add($user);
            $orderRepository->add($order);
            $basketRepository->add($user->getBasket(), true);

            // On redirige vers une page de détail
            return new Response('Commande reçu avec succès !');
        }

        // On affiche la page de validation du panier
        return $this->render('basket/validate.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
