<?php

namespace App\Controller\API;

use App\Form\RegistrationType;
use App\Form\ApiRegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('api/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_user_list', methods: ['GET'])]
    public function list(UserRepository $repository): Response
    {

        $users = $repository ->findAll();

        return $this->json($users);

        
    }

    #[Route('/{id}', name: 'app_api_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->json($user);
    }

    #[Route('', name: 'app_api_user_create', methods: ['POST'])]
    public function create(Request $request, UserRepository $repository, UserPasswordHasherInterface $hasher): Response
    {
        // Création d'un formulaire
        $form = $this->createForm(ApiRegistrationType::class);

        // On remplie le formulaire
        $form->handleRequest($request);

        // On test si il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupére l'utilisateur créé par le formulaire
            $user = $form->getData();

            // On crypte le mot de passe
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

            // Si c'est bon, on enregistre l'utilisateur
            $repository->add($user, true);

            // On retourne l'utilisateur créé en json !
            return $this->json($user, 201);
        }

        // Si ce n'est pas le cas on retourne une erreur 400 avec
        // les erreurs du formulaire en JSON
        return $this->json($form->getErrors(), 400);
    }



}
