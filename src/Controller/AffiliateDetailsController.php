<?php

// src/Controller/AffiliateDetailsController.php

namespace App\Controller;

use App\Entity\Affiliate;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AffiliateDetailsController extends AbstractController
{
    /**
     * @Route("/super_admin/users/{id}/details.json", name="user_details_json")
     */
    public function userDetailsJson(User $user): JsonResponse
    {
        $userData = [
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhoneNumber(),
        ];

        return new JsonResponse($userData);
    }

}
