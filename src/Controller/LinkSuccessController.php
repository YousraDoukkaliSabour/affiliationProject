<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\CommissionTotal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\AffiliateLink; // Importez l'entité AffiliateLink
use App\Entity\Sale; // Importez l'entité Sale


class LinkSuccessController extends AbstractController
{
    /**
     * @Route("/link-success", name="link_success")
     */
    public function index(): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        // Vérifier si l'utilisateur est connecté
        if (!$user instanceof User) {
            // Gérer le cas où l'utilisateur n'est pas connecté
            throw $this->createNotFoundException('User not found');
        }
// Récupérer le lien d'affiliation de l'utilisateur depuis la base de données
        $affiliateLink = $this->getDoctrine()->getRepository(AffiliateLink::class)->findOneBy(['user' => $user]);

        // Vérifiez si un lien d'affiliation a été trouvé pour l'utilisateur
        if (!$affiliateLink) {
            // Traitez le cas où aucun lien d'affiliation n'a été trouvé pour l'utilisateur
            throw $this->createNotFoundException('Affiliate link not found for the user');
        }
        // Concaténer le lien d'affiliation avec l'ID de l'entité AffiliateLink
        $fullAffiliateLink = $affiliateLink->getLink() . $affiliateLink->getId();
        // Récupérer les ventes associées à l'utilisateur depuis la base de données
        $sale = $this->getDoctrine()->getRepository(Sale::class)->findBy(['user' => $user]);
        // Passer les données de l'utilisateur à la vue

// Récupérer le nombre de visiteurs
        $visitorCount = count(array_unique(array_map(function($sale) {
            return $sale->getVisitorIdentifier();
        }, $sale)));


        // Find the commissions for the current affiliate link
        $commissions = [];
        if ($affiliateLink) {
            $commissions = $entityManager->getRepository(Commission::class)->findBy(['affiliateLinkId' => $affiliateLink->getId()]);
        }

        // Calculate total commission amount
        $totalAmount = array_reduce($commissions, function ($acc, $commission) {
            return $acc + $commission->getAmount();
        }, 0.0);

        // Update or create CommissionTotal entity
        $commissionTotal = $entityManager->getRepository(CommissionTotal::class)->findOneBy(['affiliateLinkId' => $affiliateLink->getId()]);
        if (!$commissionTotal) {
            $commissionTotal = new CommissionTotal();
            $commissionTotal->setAffiliateLinkId($affiliateLink->getId());
        }
        $commissionTotal->setTotalAmount($totalAmount);
        $entityManager->persist($commissionTotal);
        $entityManager->flush();



        return $this->render('affiliate/link_success.html.twig', [
            'user' => $user,
            'fullAffiliateLink' => $fullAffiliateLink,
            'sale' => $sale, // Passer les ventes à la vue


            'affiliateLink' => $affiliateLink,
            'commissions' => $commissions,
            'affiliateLinkId' => $affiliateLink->getId(), // Pass the affiliateLinkId variable
            'totalCommissions' => $totalAmount,
            'visitorCount' => $visitorCount, // Passer le nombre de visiteurs à la vue

        ]);

    }
}