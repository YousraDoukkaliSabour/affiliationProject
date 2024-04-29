<?php

namespace App\Controller;




use App\Entity\AffiliateLink;
use App\Entity\Commission;
use App\Entity\Product;
use App\Entity\Sales;
use App\Entity\User;
use App\Form\AffiliateLinkType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DashboardController extends AbstractController
{




    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(Request $request, Security $security ): Response
    {
        $user = $security->getUser();
        $userId = $user->getId();

        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve the user entity from the database
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Fetch all products
        $products = $entityManager->getRepository(Product::class)->findAll();

        $affiliateLink = new AffiliateLink();
        $form = $this->createForm(AffiliateLinkType::class, $affiliateLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affiliateLink->setUser($user);
            $affiliateLink->setLink($this->generateAffiliateLink($affiliateLink));

            $entityManager->persist($affiliateLink);
            $entityManager->flush();
            $affiliateLink->setLink($this->generateAffiliateLink($affiliateLink));



            // Redirect to the generated affiliate link
            return $this->redirect($affiliateLink->getLink());
        }

        return $this->render('affiliate/dashboard.html.twig', [
            'form' => $form->createView(),
            'affiliateLink' => $affiliateLink->getLink(),
            'products' => $products,
        ]);
    }

    private function generateAffiliateLink(AffiliateLink $affiliateLink): string
    {

        $affiliateLinkId = $affiliateLink->getId();

        // Set cookie to track the affiliate link
        setcookie('affiliateLinkId', $affiliateLinkId, time() + 3600, '/');

        // Generate the full URL including the path to the package template
        return 'http://localhost:8000/affiliate/'  . '?affiliatelink=' . $affiliateLinkId;
    }

    /**
     * @Route("/affiliate/Product-{productId}", name="app_package")
     */
    public function package(Request $request, int $productId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliatelink');

        // Use a persistent cookie to track the user
        $cookieName = 'affiliateUserId';
        $cookieValue = $request->cookies->get($cookieName);
        if (!$cookieValue) {
            $cookieValue = uniqid('user_', true); // Generate a unique identifier for the user
            $response = new Response();
            $response->headers->setCookie(new Cookie($cookieName, $cookieValue, time() + (3600 * 24 * 30))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Retrieve the affiliate link entity
        $affiliateLink = $entityManager->getRepository(AffiliateLink::class)->find($affiliateLinkId);

        // Check if there is an existing sale for the unique identifier and affiliate link
        $existingSale = $entityManager->getRepository(Sales::class)->findOneBy([
            'affiliateLinkId' => $affiliateLinkId,
            'uniqueIdentifier' => $cookieValue,
        ]);

        if (!$existingSale) {
            // Increment sales count for the affiliate link
            if ($affiliateLink) {
                $affiliateLink->setSalesCount($affiliateLink->getSalesCount() + 1);
                $entityManager->persist($affiliateLink);
                $entityManager->flush();

                // Create a new Sales entity with additional information
                $sales = new Sales();
                $sales->setAffiliateLinkId($affiliateLinkId);
                $sales->setAmount($affiliateLink->getProduct()->getPrice());
                $sales->setCreatedAt(new \DateTime());
                $sales->setUniqueIdentifier($cookieValue); // Store the unique identifier
                $sales->setUser($affiliateLink->getUser()); // Set the user property from the affiliate link
                $entityManager->persist($sales);
                $entityManager->flush();

                $amount = $sales->getAmount();
                switch ($amount) {
                    case 49.99:
                        $commissionPercentage = 0.05; // 5%
                        break;
                    case 99.99:
                        $commissionPercentage = 0.10; // 10%
                        break;
                    case 149.99:
                        $commissionPercentage = 0.15; // 15%
                        break;
                    case 199.99:
                        $commissionPercentage = 0.20; // 20%
                        break;
                    case 599.99:
                        $commissionPercentage = 0.25; // 25%
                        break;
                    case 1199.99:
                        $commissionPercentage = 0.30; // 30%
                        break;
                    case 1799.99:
                        $commissionPercentage = 0.35; // 35%
                        break;
                    case 2399.99:
                        $commissionPercentage = 0.40; // 40%
                        break;
                    default:
                        // Handle any other amounts or set a default commission percentage
                }

                // Create a new Commission entity
                $commission = new Commission();
                $commission->setPercentage($commissionPercentage);
                $commission->setAmount($sales->getAmount());
                $commission->setSale($sales); // Set the sale directly
                $userId = $sales->getUser()->getId();
                $commission->setUserId($userId); // Set the user_id from the Sales entity
                $entityManager->persist($commission);
                $entityManager->flush();
            }
        }

        // Assuming you have a template for each package like "package1.html.twig", "package2.html.twig", etc.
        $templateName = 'package' . $productId . '.html.twig';

        return $this->render('package/' . $templateName, [
            'productId' => $productId,
        ]);
    }





}