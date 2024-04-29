<?php

namespace App\Controller;

use App\Entity\AffiliateLink;
use App\Entity\Sale;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PackageViewController extends AbstractController
{
    public function package1(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliateLinkId');

        // Check if visitor identifier cookie exists; if not, generate a new one
        $visitorIdentifier = $request->cookies->get('visitorIdentifier');
        if (!$visitorIdentifier) {
            $visitorIdentifier = uniqid('visitor_', true);
            $response = new Response();
            $response->headers->setCookie(new Cookie('visitorIdentifier', $visitorIdentifier, time() + (30 * 24 * 60 * 60))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Assuming you have a method to retrieve the user ID associated with the affiliate link
        $userId = $this->getUserIdFromAffiliateLink($affiliateLinkId); // Implement this method accordingly
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a new Sale entity instance
        $sale = new Sale();
        $sale->setUser($user); // Set the user associated with the affiliate link
        $sale->setVisitorIdentifier($visitorIdentifier);
        $sale->setPricingPlans('package1'); // Set the pricing plan based on the package
        $entityManager->persist($sale);
        $entityManager->flush();

        // Render the package view template
        return $this->render('package/package1.html.twig');




        }

    private function getUserIdFromAffiliateLink($affiliateLinkId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve the affiliate link entity from the database
        $affiliateLink = $entityManager->getRepository(AffiliateLink::class)->find($affiliateLinkId);

        // Check if the affiliate link entity exists and has a user associated with it
        if ($affiliateLink && $affiliateLink->getUser()) {
            // Return the user ID associated with the affiliate link
            return $affiliateLink->getUser()->getId();
        }

        // If the affiliate link or associated user does not exist, return null or handle accordingly
        return null;
    }

    public function package2(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliateLinkId');

        // Check if visitor identifier cookie exists; if not, generate a new one
        $visitorIdentifier = $request->cookies->get('visitorIdentifier');
        if (!$visitorIdentifier) {
            $visitorIdentifier = uniqid('visitor_', true);
            $response = new Response();
            $response->headers->setCookie(new Cookie('visitorIdentifier', $visitorIdentifier, time() + (30 * 24 * 60 * 60))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Assuming you have a method to retrieve the user ID associated with the affiliate link
        $userId = $this->getUserIdFromAffiliateLink($affiliateLinkId); // Implement this method accordingly
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a new Sale entity instance
        $sale = new Sale();
        $sale->setUser($user); // Set the user associated with the affiliate link
        $sale->setVisitorIdentifier($visitorIdentifier);
        $sale->setPricingPlans('package2'); // Set the pricing plan based on the package
        $entityManager->persist($sale);
        $entityManager->flush();


        return $this->render('package/package2.html.twig');
    }

    public function package3(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliateLinkId');

        // Check if visitor identifier cookie exists; if not, generate a new one
        $visitorIdentifier = $request->cookies->get('visitorIdentifier');
        if (!$visitorIdentifier) {
            $visitorIdentifier = uniqid('visitor_', true);
            $response = new Response();
            $response->headers->setCookie(new Cookie('visitorIdentifier', $visitorIdentifier, time() + (30 * 24 * 60 * 60))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Assuming you have a method to retrieve the user ID associated with the affiliate link
        $userId = $this->getUserIdFromAffiliateLink($affiliateLinkId); // Implement this method accordingly
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a new Sale entity instance
        $sale = new Sale();
        $sale->setUser($user); // Set the user associated with the affiliate link
        $sale->setVisitorIdentifier($visitorIdentifier);
        $sale->setPricingPlans('package3'); // Set the pricing plan based on the package
        $entityManager->persist($sale);
        $entityManager->flush();



        return $this->render('package/package3.html.twig');
    }

    public function package4(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliateLinkId');

        // Check if visitor identifier cookie exists; if not, generate a new one
        $visitorIdentifier = $request->cookies->get('visitorIdentifier');
        if (!$visitorIdentifier) {
            $visitorIdentifier = uniqid('visitor_', true);
            $response = new Response();
            $response->headers->setCookie(new Cookie('visitorIdentifier', $visitorIdentifier, time() + (30 * 24 * 60 * 60))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Assuming you have a method to retrieve the user ID associated with the affiliate link
        $userId = $this->getUserIdFromAffiliateLink($affiliateLinkId); // Implement this method accordingly
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a new Sale entity instance
        $sale = new Sale();
        $sale->setUser($user); // Set the user associated with the affiliate link
        $sale->setVisitorIdentifier($visitorIdentifier);
        $sale->setPricingPlans('package4'); // Set the pricing plan based on the package
        $entityManager->persist($sale);
        $entityManager->flush();



        return $this->render('package/package4.html.twig');
    }

    public function package5(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliateLinkId');

        // Check if visitor identifier cookie exists; if not, generate a new one
        $visitorIdentifier = $request->cookies->get('visitorIdentifier');
        if (!$visitorIdentifier) {
            $visitorIdentifier = uniqid('visitor_', true);
            $response = new Response();
            $response->headers->setCookie(new Cookie('visitorIdentifier', $visitorIdentifier, time() + (30 * 24 * 60 * 60))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Assuming you have a method to retrieve the user ID associated with the affiliate link
        $userId = $this->getUserIdFromAffiliateLink($affiliateLinkId); // Implement this method accordingly
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a new Sale entity instance
        $sale = new Sale();
        $sale->setUser($user); // Set the user associated with the affiliate link
        $sale->setVisitorIdentifier($visitorIdentifier);
        $sale->setPricingPlans('package5'); // Set the pricing plan based on the package
        $entityManager->persist($sale);
        $entityManager->flush();



        return $this->render('package/package5.html.twig');
    }

    public function package6(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliateLinkId');

        // Check if visitor identifier cookie exists; if not, generate a new one
        $visitorIdentifier = $request->cookies->get('visitorIdentifier');
        if (!$visitorIdentifier) {
            $visitorIdentifier = uniqid('visitor_', true);
            $response = new Response();
            $response->headers->setCookie(new Cookie('visitorIdentifier', $visitorIdentifier, time() + (30 * 24 * 60 * 60))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Assuming you have a method to retrieve the user ID associated with the affiliate link
        $userId = $this->getUserIdFromAffiliateLink($affiliateLinkId); // Implement this method accordingly
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a new Sale entity instance
        $sale = new Sale();
        $sale->setUser($user); // Set the user associated with the affiliate link
        $sale->setVisitorIdentifier($visitorIdentifier);
        $sale->setPricingPlans('package6'); // Set the pricing plan based on the package
        $entityManager->persist($sale);
        $entityManager->flush();



        return $this->render('package/package6.html.twig');
    }

    public function package7(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliateLinkId');

        // Check if visitor identifier cookie exists; if not, generate a new one
        $visitorIdentifier = $request->cookies->get('visitorIdentifier');
        if (!$visitorIdentifier) {
            $visitorIdentifier = uniqid('visitor_', true);
            $response = new Response();
            $response->headers->setCookie(new Cookie('visitorIdentifier', $visitorIdentifier, time() + (30 * 24 * 60 * 60))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Assuming you have a method to retrieve the user ID associated with the affiliate link
        $userId = $this->getUserIdFromAffiliateLink($affiliateLinkId); // Implement this method accordingly
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a new Sale entity instance
        $sale = new Sale();
        $sale->setUser($user); // Set the user associated with the affiliate link
        $sale->setVisitorIdentifier($visitorIdentifier);
        $sale->setPricingPlans('package7'); // Set the pricing plan based on the package
        $entityManager->persist($sale);
        $entityManager->flush();



        return $this->render('package/package7.html.twig');
    }

    public function package8(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Retrieve affiliate link ID from the query parameters
        $affiliateLinkId = $request->query->get('affiliateLinkId');

        // Check if visitor identifier cookie exists; if not, generate a new one
        $visitorIdentifier = $request->cookies->get('visitorIdentifier');
        if (!$visitorIdentifier) {
            $visitorIdentifier = uniqid('visitor_', true);
            $response = new Response();
            $response->headers->setCookie(new Cookie('visitorIdentifier', $visitorIdentifier, time() + (30 * 24 * 60 * 60))); // Set cookie to expire in 30 days
            $response->send();
        }

        // Assuming you have a method to retrieve the user ID associated with the affiliate link
        $userId = $this->getUserIdFromAffiliateLink($affiliateLinkId); // Implement this method accordingly
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Create a new Sale entity instance
        $sale = new Sale();
        $sale->setUser($user); // Set the user associated with the affiliate link
        $sale->setVisitorIdentifier($visitorIdentifier);
        $sale->setPricingPlans('package8'); // Set the pricing plan based on the package
        $entityManager->persist($sale);
        $entityManager->flush();



        return $this->render('package/package8.html.twig');
    }




}