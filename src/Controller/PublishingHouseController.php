<?php

namespace App\Controller;

use App\Form\AdminPublishingHouseType;
use App\Repository\PublishingHouseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublishingHouseController extends AbstractController
{
    #[Route('/publishing/house/creation', name: 'app_publishing_house_create')]
    public function create(Request $request, PublishingHouseRepository $repository): Response
    {
         // Création du formulaire
         $form = $this->createForm(AdminPublishingHouseType::class);

         // On remplie le formulaire avec les données envoyé par l'utilisateur
         $form->handleRequest($request);
 
         // Tester si le formulaire est envoyé et est valide
         if ($form->isSubmitted() && $form->isValid()) {
             // récupération de l'auteur
             $house = $form->getData();
 
             // enregistrement des données en base de données
             $repository->add($house, true);
             // Redirection vers la page de la liste
             return $this->redirectToRoute('app_publishing_house_list');
         }

         // Afficher la page
        return $this->render('publishing_house/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/publishing/house', name: 'app_publishing_house_list')]
    public function list(PublishingHouseRepository $repository): Response
    {
        // Récupérer les auteurs depuis la base de donnés
        $houses = $repository->findAll();

        // Afficher la page HTML
        return $this->render('publishing_house/list.html.twig', [
            'houses' => $houses,
        ]);
    }



    #[Route('/publishing/house/{id}', name: 'app_publishing_house_update')]
    public function update(int $id, Request $request, PublishingHouseRepository $repository): Response
    {
        // Récuperation de l'auteur
        $house = $repository->find($id);

        // Création du formulaire
        $form = $this->createForm(AdminPublishingHouseType::class, $house);

        // Remplir le formulaire avec les données de l'utilisateur
        $form->handleRequest($request);

        // Tester si le formulaire est envoyé et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // récupération de l'auteur
            $house = $form->getData();

            // enregistrement des données en base de données
            $repository->add($house, true);

            // Redirection vers la page de la liste
            return $this->redirectToRoute('app_publishing_house_list');
        }

        // Afficher la page
        return $this->render('publishing_house/update.html.twig', [
            'form' => $form->createView(),
            'house' => $house,
        ]);
    }


    #[Route('/publishing/house/{id}/supprimer', name: 'app_publishing_house_remove')]
    public function remove(int $id, PublishingHouseRepository $repository): Response
    {
        // Récupération de l'auteur depuis son id
        $house = $repository->find($id);

        // Supprimer l'auteur de la base de données
        $repository->remove($house, true);

        // Rediriger vers la liste
        return $this->redirectToRoute('app_publishing_house_list');
    }



}
