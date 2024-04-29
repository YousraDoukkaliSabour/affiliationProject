<?php

namespace App\Controller;

use App\Entity\AffiliateLink;
use App\Entity\Commission;
use App\Entity\Sale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommissionController extends AbstractController
{
    /**
     * @Route("/calculate-commissions", name="calculate_commissions")
     */
    public function calculateCommissions(): Response
    {
        // Get the EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Delete all existing commissions
        $commissionRepository = $entityManager->getRepository(Commission::class);
        $existingCommissions = $commissionRepository->findAll();
        foreach ($existingCommissions as $existingCommission) {
            $entityManager->remove($existingCommission);
        }

        // Flush the changes to delete existing commissions
        $entityManager->flush();

        // Fetch all Sales
        $sales = $entityManager->getRepository(Sale::class)->findAll();

        // Define package prices
        $packagePrices = [
            'package1' => 10,
            'package2' => 15,
            'package3' => 20,
            'package4' => 25,
            'package5' => 30,
            'package6' => 35,
            'package7' => 40,
            'package8' => 45,
        ];

        // Iterate over each Sale instance
        foreach ($sales as $sale) {
            // Retrieve the AffiliateLink corresponding to the Sale's user_id
            $affiliateLink = $this->getAffiliateLinkByUserId($sale->getUser()->getId());

            if ($affiliateLink) {
                // Determine the package price based on the pricing_plans field
                $pricingPlan = $sale->getPricingPlans();
                $packagePrice = $packagePrices[$pricingPlan] ?? 0;

                // Get the commission percentage from the AffiliateLink entity
                $commissionPercentage = $this->getCommissionPercentage($affiliateLink, $pricingPlan);

                // Calculate the commission amount
                $commissionAmount = $packagePrice * ($commissionPercentage / 100);

                // Store the commission in the Commission entity
                $commission = new Commission();
                $commission->setAffiliateLinkId($affiliateLink->getId());
                $commission->setAmount($commissionAmount);

                $entityManager->persist($commission);
            }
        }

        // Flush all changes to the database
        $entityManager->flush();

        // Fetch all commissions after they have been calculated
        $commissions = $commissionRepository->findAll();

        // Render the template with commission data
        return $this->render('comissions/comissionview.html.twig', [
            'commissions' => $commissions,
        ]);
    }

    private function getAffiliateLinkByUserId(int $userId)
    {
        // Get the EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve the AffiliateLink entity based on the user_id
        $affiliateLink = $entityManager->getRepository(AffiliateLink::class)->findOneBy(['user' => $userId]);

        return $affiliateLink;
    }

    private function getCommissionPercentage($affiliateLink, $pricingPlan)
    {
        // Determine the commission percentage based on the pricing plan and AffiliateLink entity
        // For simplicity, let's assume the commission percentage is stored directly in the AffiliateLink entity

        // Example: If the pricing plan is 'package1', return the value of plan1 from the AffiliateLink entity
        $planField = 'plan' . substr($pricingPlan, -1); // Extract the package number from the pricing plan
        $commissionPercentage = $affiliateLink->{'get' . ucfirst($planField)}(); // Assuming getters are generated for plan fields

        return $commissionPercentage;
    }
}
