<?php

namespace App\Controller;

use App\Entity\Affiliate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListAffiliateController extends AbstractController
{
    /**
     * @Route("/super_admin/affiliates", name="super_admin_affiliates")
     */
    public function affiliatesList(): Response
    {
        $affiliates = $this->getDoctrine()->getRepository(Affiliate::class)->findAll();

        return $this->render('admin/affiliatesList.html.twig', [
            'affiliates' => $affiliates,
        ]);
    }
}
