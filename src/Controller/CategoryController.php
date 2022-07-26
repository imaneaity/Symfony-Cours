<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Form\AdminCategoryType;


use App\Form\SearchCategoryType;
use App\DTO\SearchCategoryCriteria;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/admin/categories/nouveau', name: 'app_admin_category_create')]
    public function create(Request $request, CategoryRepository $repository): Response
    {


        $form = $this->createForm(AdminCategoryType::class);
        // On remplie le formulaire avec les données envoyé par l'utilisateur
        $form->handleRequest($request);

        // Tester si le formulaire est envoyé et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // récupération de l'auteur
            $category = $form->getData();

            // enregistrer l'categorie grâce au répository
            $repository->add($category, true);

            // Rediriger l'utilisateur vers la liste des categories
            return $this->redirectToRoute('app_admin_category_list', [
                
            ]);
        }

        // afficher le formulaire (la page twig)
        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/categories', name: 'app_admin_category_list')]
    public function list(CategoryRepository $repository, Request $request): Response
    {
        // Création des critéres de recherche
        $criteria = new SearchCategoryCriteria();

        // Création du formulaire avec les critéres de recherche
        $form = $this->createForm(SearchCategoryType::class, $criteria);

        // On remplie le formulaire avec les données envoyer par l'utilisateur
        $form->handleRequest($request);

        // On récupére toutes les catégories filtré
        $categories = $repository->findByCriteria($criteria);

        // Afficher la page HTML
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/categories/{id}', name: 'app_admin_category_update')] 
    public function update(int $id, Request $request, CategoryRepository $repository): Response
    {
        // récupérer l'categorie à partir de l'id
        $category = $repository->find($id);

        // Création du formulaire
        $form = $this->createForm(AdminCategoryType::class, $category);

        // Remplir le formulaire avec les données de l'utilisateur
        $form->handleRequest($request);

        // Tester si le formulaire est envoyé et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // récupération de l'auteur
            $category = $form->getData();

            // Enregistrer l'categorie
            $repository->add($category, true);

            // rediriger vers la page liste
            return $this->redirectToRoute('app_admin_category_list');
        }

        // afficher le formulaire de mise à jour de l'categorie
        return $this->render('category/update.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/admin/categories/{id}/supprimer', name: 'app_admin_category_remove')]
    public function remove(int $id, CategoryRepository $repository): Response
    {
        // Récupération de l'categorie depuis son id
        $category = $repository->find($id);

        // Supprimer l'categorie de la base de données
        $repository->remove($category, true);

        // Rediriger vers la liste
        return $this->redirectToRoute('app_admin_category_list');
    }
}
