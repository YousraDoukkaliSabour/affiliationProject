<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdateFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserUpdateController extends AbstractController
{
    /*
    /**
     * @Route("/account/settings", name="account_settings")
     */

    public function update(Request $request): Response
    {
        // Récupération de l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Création du formulaire pour la mise à jour des informations de l'utilisateur
        $form = $this->createForm(UserUpdateFormType::class, $user);

        // Traitement de la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement des modifications dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Redirection de l'utilisateur vers une autre page ou affichage d'un message de succès
            $this->addFlash('success', 'Your profile has been updated successfully.');
            return $this->redirectToRoute('account_settings');
        }

        // Affichage du formulaire et des données de l'utilisateur
        return $this->render('account/settings.html.twig', [
            'user' => $user, // Passage des données de l'utilisateur au template
            'form' => $form->createView(), // Création de la vue du formulaire pour l'affichage dans le template
        ]);
    }

}
