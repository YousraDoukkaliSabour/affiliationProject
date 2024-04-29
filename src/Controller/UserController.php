<?php

namespace App\Controller;

use App\Entity\AffiliateLink;
use App\Entity\Commission;
use App\Entity\CommissionTotal;
use App\Entity\Sale;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/affiliate/{affiliateId}", name="show_affiliate_info")
     */
    public function showAffiliateInfo($affiliateId): Response
    {

        // Fetch affiliate link information based on $affiliateId from the database
        // Fetch affiliate link information based on $affiliateId from the database
        $affiliateLinkRepository = $this->getDoctrine()->getRepository(AffiliateLink::class);
        $affiliateLink = $affiliateLinkRepository->find($affiliateId);

// Check if affiliate link with the given ID exists
        if (!$affiliateLink) {
            throw $this->createNotFoundException('Affiliate link not found');
        }

// Get the user associated with the affiliate link
        $user = $affiliateLink->getUser();
        $entityManager = $this->getDoctrine()->getManager();

// Check if user exists
        if (!$user) {
            throw $this->createNotFoundException('User not found for the affiliate link');
        }

// Get user's first name and last name
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $email = $user->getEmail();
        $id = $user->getId();
        $phoneNumber = $user->getPhoneNumber();
        $address = $user->getAddress();

// Récupérer le lien d'affiliation
        $link = $affiliateLink->getLink();

// Récupérer l'identifiant du lien d'affiliation
        $linkId = $affiliateLink->getId();

// Concaténer le lien avec son identifiant
        $fullAffiliateLink = $link . $linkId;

// Récupérer les ventes associées à l'utilisateur depuis la base de données
        $sale = $this->getDoctrine()->getRepository(Sale::class)->findBy(['user' => $user]);

// Initialize an array to store the data to be displayed in the table
        $tableData = [];

// Iterate over each Sale instance to collect data for the table
        foreach ($sale as $saleItem) {

            // Retrieve Commission data corresponding to the AffiliateLink
            $commission = null;

            // If commission is not null, refresh it to get the updated value
            if ($commission) {
                $entityManager->refresh($commission);
            }

            // Retrieve the User corresponding to the affiliateLink
            $user = $affiliateLink ? $affiliateLink->getUser() : null;

            // Prepare data for the table
            $tableData[] = [
                'visitorIdentifier' => $saleItem->getVisitorIdentifier(),
                'affiliateLinkId' => $affiliateLink ? $affiliateLink->getId() : null,
                'affiliateName' => $user ? $user->getFirstname() . ' ' . $user->getLastname() : 'Unknown',
                'pricingPlan' => $saleItem->getPricingPlans(),
                'commissionAmount' => $commission ? $commission->getAmount() : null,
            ];
        }

// Récupérer le nombre de visiteurs
        $visitorCount = count(array_unique(array_map(function($saleItem) {
            return $saleItem->getVisitorIdentifier();
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

// Render the Twig template with user information
        return $this->render('admin/affiliateDetails.html.twig', [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'fullAffiliateLink' => $fullAffiliateLink,
            'id' => $id,
            'phoneNumber'=> $phoneNumber,
            'address' => $address,
            'sale' => $sale, // Passer les ventes à la vue
            'affiliateLink' => $affiliateLink,
            'commissions' => $commissions,
            'affiliateLinkId' => $affiliateLink->getId(), // Pass the affiliateLinkId variable
            'totalCommissions' => $totalAmount,
            'visitorCount' => $visitorCount, // Passer le nombre de visiteurs à la vue
            'tableData' => $tableData,
        ]);

    }

}
