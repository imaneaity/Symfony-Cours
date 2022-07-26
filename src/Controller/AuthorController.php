<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Author;
use App\Form\AdminAuthorType;
use App\Form\SearchAuthorType;
use App\DTO\SearchAuthorCriteria;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    #[Route('auteurs/nouveau', name: 'app_admin_author_create')]
    public function create(Request $request, AuthorRepository $repository): Response
    {
        // Création du formulaire
        $form = $this->createForm(AdminAuthorType::class);

        // On remplie le formulaire avec les données envoyé par l'utilisateur
        $form->handleRequest($request);

        // Tester si le formulaire est envoyé et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // récupération de l'auteur
            $author = $form->getData();

            // enregistrement des données en base de données
            $repository->add($author, true);

            // Redirection vers la page de la liste
            return $this->redirectToRoute('app_admin_author_list');
        }

        // Afficher la page
        return $this->render('author/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('auteurs', name: 'app_admin_author_list')]
    public function list(AuthorRepository $repository, Request $request): Response
    {
        // Création des critères de recherche
        $criteria = new SearchAuthorCriteria();

        // Création du formulaire
        $form = $this->createForm(SearchAuthorType::class, $criteria);

        // On remplie le formulaire avec les données de l'utilisateur
        $form->handleRequest($request);


        // Récupérer les auteurs depuis la base de donnés
        $authors = $repository->findByCriteria($criteria);

        // Afficher la page HTML
        return $this->render('author/list.html.twig', [
            'authors' => $authors,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/auteurs/{id}', name: 'app_admin_author_update')]
    public function update(int $id, Request $request, AuthorRepository $repository): Response
    {
        // Récuperation de l'auteur
        $author = $repository->find($id);

        // Création du formulaire
        $form = $this->createForm(AdminAuthorType::class, $author);

        // Remplir le formulaire avec les données de l'utilisateur
        $form->handleRequest($request);

        // Tester si le formulaire est envoyé et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // récupération de l'auteur
            $author = $form->getData();

            // enregistrement des données en base de données
            $repository->add($author, true);

            // Redirection vers la page de la liste
            return $this->redirectToRoute('app_admin_author_list');
        }

        // Afficher la page
        return $this->render('author/update.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }

    #[Route('/admin/auteurs/{id}/supprimer', name: 'app_admin_author_remove')]
    public function remove(int $id, AuthorRepository $repository): Response
    {
        // Récupération de l'auteur depuis son id
        $author = $repository->find($id);

        // Supprimer l'auteur de la base de données
        $repository->remove($author, true);

        // Rediriger vers la liste
        return $this->redirectToRoute('app_admin_author_list');
    }
}
