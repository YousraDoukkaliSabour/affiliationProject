<?php

namespace App\Controller;

use App\Entity\Affiliate;
use App\Entity\AffiliateLink;
use App\Entity\Commission;
use App\Entity\CommissionTotal;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/register/affiliate", name="admin_register_affiliate")
     */
    public function registerAffiliate(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Your logic for registering affiliate users goes here
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword() // Use getPassword() directly
                )
            );

            // Save the user to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Create an affiliate entity for the user
            $affiliate = new Affiliate();
            $affiliate->setUser($user);
            $affiliate->setName('name'); // Placeholder name

            // Save the affiliate to the database
            $entityManager->persist($affiliate);
            $entityManager->flush();








            // Store the user ID in the session or pass it along as a parameter
            $this->get('session')->set('user_id', $user->getId());



            // Return a JSON response indicating success
            return new JsonResponse(['success' => true]);
        }

        return $this->render('admin/register_affiliate.html.twig', [
            'registrationForm' => $form->createView(),

        ]);
    }


/**
 * @Route("/affiliate/success", name="affiliate_success")
 */
public function affiliateSuccess(Request $request): Response
{
    // Get the affiliate link ID from the route parameters
    $affiliateLinkId = $request->query->get('affiliateLinkId');

    // Find the affiliate link entity based on the ID
    $affiliateLink = $this->getDoctrine()->getRepository(AffiliateLink::class)->find($affiliateLinkId);

    if (!$affiliateLink) {
        return new Response('Affiliate link not found', Response::HTTP_NOT_FOUND);
    }

    // Pass the affiliate link URL to the template
    return $this->render('affiliate/addsuccess.html.twig', [
        'affiliateLink' => $affiliateLink->getLink().$affiliateLinkId,
    ]);
 }

    /**
     * @Route("/admin/generate-affiliate-link", name="admin_generate_affiliate_link")
     */
    public function generateAffiliateLink(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the user ID from the session
        $userId = $this->get('session')->get('user_id');

        // Find the user entity
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Find the affiliate entity for the user
        $affiliate = $entityManager->getRepository(Affiliate::class)->findOneBy(['user' => $user]);

        if (!$affiliate) {
            return new Response('Affiliate not found', Response::HTTP_NOT_FOUND);
        }

        // Create an affiliate link entity for the affiliate
        $affiliateLink = new AffiliateLink();
        $affiliateLink->setAffiliate($affiliate);
        $affiliateLink->setUser($user); // Set the user directly

        // Set the plan values from the request parameters
        $affiliateLink->setPlan1($request->query->get('plan1'));
        $affiliateLink->setPlan2($request->query->get('plan2'));
        $affiliateLink->setPlan3($request->query->get('plan3'));
        $affiliateLink->setPlan4($request->query->get('plan4'));
        $affiliateLink->setPlan5($request->query->get('plan5'));
        $affiliateLink->setPlan6($request->query->get('plan6'));
        $affiliateLink->setPlan7($request->query->get('plan7'));
        $affiliateLink->setPlan8($request->query->get('plan8'));

        // Generate the affiliate link using the ID of the affiliate link entity
        $link = 'http://localhost:8000/affiliate/affiliatelink=' . $affiliateLink->getId();

        // Set the generated link in the affiliate link entity
        $affiliateLink->setLink($link);



        // Persist the affiliate link entity
        $entityManager->persist($affiliateLink);
        $entityManager->flush();

        // Redirect to the affiliate_success route with the affiliate link ID
        return $this->redirectToRoute('affiliate_success', ['affiliateLinkId' => $affiliateLink->getId()]);
    }

    /**
     * @Route("/affiliate/affiliatelink={affiliateLinkId}", name="app_package_view")
     */
    public function packageView(Request $request, int $affiliateLinkId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve the affiliate link entity
        $affiliateLink = $entityManager->getRepository(AffiliateLink::class)->find($affiliateLinkId);

        // Use a persistent cookie to track the user
        $cookieName = 'affiliateViewedLinkId';
        $cookieValue = $request->cookies->get($cookieName);
        if (!$cookieValue || $cookieValue != $affiliateLinkId) {
            $response = new Response();
            $response->headers->setCookie(new Cookie($cookieName, $affiliateLinkId, time() + (3600 * 24 * 30))); // Set cookie to expire in 30 days
            $response->send();

            // Increment the sales count for the affiliate link
            if ($affiliateLink) {
                $affiliateLink->setSalesCount($affiliateLink->getSalesCount() + 1);
                $entityManager->persist($affiliateLink);
                $entityManager->flush();
            }
        }

        // Render the package view template
        return $this->render('package/package_view.html.twig', [
            'affiliateLinkId' => $affiliateLinkId,
        ]);
    }

    /**
     * @Route("/admin/commission-requests", name="commission_requests")
     */
    public function commissionRequests(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Fetch the commission totals where commissionRequested is true
        $commissionTotals = $entityManager->getRepository(CommissionTotal::class)->findBy(['commissionRequested' => true]);

        return $this->render('admin/commission_requests.html.twig', [
            'commissionTotal' => $commissionTotals,
        ]);
        }


    /**
     * @Route("/admin/give-commission/{affiliateLinkId}", name="give_commission", methods={"POST"})
     */
    public function giveCommission(Request $request, int $affiliateLinkId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Find the commission total entity
        $commissionTotal = $entityManager->getRepository(CommissionTotal::class)->findOneBy(['affiliateLinkId' => $affiliateLinkId]);

        if (!$commissionTotal) {
            return new Response('Commission total not found', Response::HTTP_NOT_FOUND);
        }

        // Reset the total amount to 0
        $commissionTotal->setTotalAmount(0);
        $commissionTotal->setCommissionRequested(false); // Set commission_requested to false
        $entityManager->persist($commissionTotal);
        $entityManager->flush();

        // Redirect back to the commission requests page
        return $this->redirectToRoute('commission_requests');
    }



    /**
     * @Route("/super_admin/overview",name="overviewAdmin")
     * @return Response
     */
    public function overview(Request $request): Response
    {
        $user = $request->getSession()->get('user');

        return $this->render('admin/overview.html.twig', [
            'user' => $user
        ]);
    }






    /**
     * @Route("/super_admin/affiliateDetails",name="affiliateDetails")
     * @return Response
     */
    public function affiliateDetails(Request $request): Response
    {
        $user = $request->getSession()->get('user');

        return $this->render('admin/affiliateDetails.html.twig', [
            'user' => $user
        ]);
    }




}