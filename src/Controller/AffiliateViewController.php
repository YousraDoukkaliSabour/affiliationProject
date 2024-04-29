<?php

namespace App\Controller;

use App\Entity\AffiliateLink;
use App\Entity\Commission;
use App\Entity\CommissionTotal;
use App\Entity\Sales;
use App\Message\NotificationMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AffiliateViewController extends AbstractController
{


    /**
     * @Route("/affiliate/view", name="affiliate_view")
     */
    public function affiliateView(Security $security): Response
    {
        $user = $this->getUser(); // Get the current user

        $entityManager = $this->getDoctrine()->getManager();

        // Find the affiliate link corresponding to the user's ID
        $affiliateLink = $entityManager->getRepository(AffiliateLink::class)->findOneBy(['user' => $user->getId()]);

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

        return $this->render('affiliate/view.html.twig', [
            'affiliateLink' => $affiliateLink,
            'commissions' => $commissions,
            'affiliateLinkId' => $affiliateLink->getId(), // Pass the affiliateLinkId variable
            'totalCommissions' => $totalAmount,
        ]);
    }

    /**
     * @Route("/affiliate/request-commission", name="request_commission", methods={"POST"})
     */
    public function requestCommission(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the affiliate link ID from the form submission
        $affiliateLinkId = $request->request->get('affiliateLinkId');

        // Store the affiliate link ID in the session
        $this->get('session')->set('affiliate_link_id', $affiliateLinkId);

        // Find the CommissionTotal entity for the affiliate link
        $commissionTotal = $entityManager->getRepository(CommissionTotal::class)->findOneBy(['affiliateLinkId' => $affiliateLinkId]);

        if (!$commissionTotal) {
            return new Response('Commission total not found', Response::HTTP_NOT_FOUND);
        }

        // Set commissionRequested to true
        $commissionTotal->setCommissionRequested(true);
        $entityManager->persist($commissionTotal);
        $entityManager->flush();

        return $this->redirectToRoute('affiliate_view', ['affiliateLinkId' => $affiliateLinkId]);
    }
}