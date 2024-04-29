<?php

namespace App\Controller;

use App\Entity\Affiliate;
use App\Entity\AffiliateLink;
use App\Entity\Commission;
use App\Entity\Sale;
use App\Entity\User;
use App\Form\ConnexionType;
use App\Form\RegistrationFormType;
use App\Form\UserUpdateFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

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

            // Redirect to the login page or any other page
            return $this->redirectToRoute('link_success');

        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();



        return $this->render('registration/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }


    /**
     * @Route("/admin/success", name="admin_success")
     */
    public function adminSuccess(): Response
    {
        // Get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Fetch Sales data
        $sales = $entityManager->getRepository(Sale::class)->findAll();

        // Initialize an array to store the data to be displayed in the table
        $tableData = [];

        // Iterate over each Sale instance to collect data for the table
        foreach ($sales as $sale) {
            // Retrieve AffiliateLink corresponding to the Sale's user_id
            $affiliateLink = $this->getAffiliateLinkByUserId($sale->getUser()->getId());

            // Retrieve Commission data corresponding to the AffiliateLink
            $commission = null;
            if ($affiliateLink) {
                $commission = $this->getCommissionByAffiliateLinkId($affiliateLink->getId());
            }

            // If commission is not null, refresh it to get the updated value
            if ($commission) {
                $entityManager->refresh($commission);
            }

            // Retrieve the User corresponding to the affiliateLink
            $user = $affiliateLink ? $affiliateLink->getUser() : null;

            // Prepare data for the table
            $tableData[] = [
                'visitorIdentifier' => $sale->getVisitorIdentifier(),

                'affiliateLinkId' => $affiliateLink ? $affiliateLink->getId() : null,

                'affiliateName' => $user ? $user->getFirstname() . ' ' . $user->getLastname() : 'Unknown',
                'pricingPlan' => $sale->getPricingPlans(),
                'commissionAmount' => $commission ? $commission->getAmount() : null,
                'commissionPercentage' => $affiliateLink ? $this->getCommissionPercentage($affiliateLink, $sale->getPricingPlans()) : null,
            ];
        }

        // Render the template with the table data
        return $this->render('registration/admin_success.html.twig', [
            'tableData' => $tableData,
        ]);
    }


    public function getAffiliateLinkByUserId(int $userId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $affiliateLink = $entityManager->getRepository(AffiliateLink::class)->findOneBy(['user' => $userId]);
        return $affiliateLink;
    }

    public function getCommissionByAffiliateLinkId(int $affiliateLinkId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commission = $entityManager->getRepository(Commission::class)->findOneBy(['affiliateLinkId' => $affiliateLinkId]);
        return $commission;
    }

    public function getCommissionPercentage(AffiliateLink $affiliateLink, string $pricingPlan)
    {
        // Assuming the commission percentage is stored directly in the AffiliateLink entity
        $planField = 'plan' . substr($pricingPlan, -1);
        return $affiliateLink->{'get' . ucfirst($planField)}();
    }

    /**
     * @Route("/logout",name="logout")
     */

    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();

        return $this->render('registration/register.html.twig', [

        ]);

    }





    //hadchi li zdt khas ytmsa7

    /**
     * @Route("/account/referrals", name="account_referrals")
     */
    public function referrals(Request $request): Response
    {
        return $this->render('account/referrals.html.twig');
    }

    /**
     * @Route("/account/overview", name="account_overview")
     */
    public function overview(): Response
    {
        $user = $this->getUser();

        return $this->render('account/overview.html.twig', [
            'user' => $user
        ]);    }

    /**
     * @Route("/account/settings", name="account_settings")
     */
    public function settings(Request $request): Response
    {
        $user = $this->getUser();

        // Créer un formulaire en utilisant la classe de formulaire UserUpdateFormType
        $form = $this->createForm(UserUpdateFormType::class, $user);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une autre page après avoir enregistré les modifications
            return $this->redirectToRoute('account_settings');
        }

        // Afficher le formulaire
        return $this->render('account/settings.html.twig', [
            'form' => $form->createView(),
            'user' => $user, // Assurez-vous de passer la variable 'user' au template
        ]);
    }



    /**
     * @Route("/account/security", name="account_security")
     */
    public function security(): Response
    {
        $user = $this->getUser();

        return $this->render('account/security.html.twig', [
            'user' => $user
        ]);    }

    /**
     * @Route("/account/billing", name="account_billing")
     */
    public function billing(): Response
    {
        $user = $this->getUser();

        return $this->render('account/billing.html.twig', [
            'user' => $user
        ]);    }

    /**
     * @Route("/account/logs", name="account_logs")
     */
    public function logs(): Response
    {
        $user = $this->getUser();
        $sale = $this->getDoctrine()->getRepository(Sale::class)->findBy(['user' => $user]);

        return $this->render('account/logs.html.twig', [
            'user' => $user,
            'sale' => $sale, // Passer les ventes à la vue

        ]);    }

    /**
     * @Route("/dashboard/analytics", name="analytics")
     */
    public function analytics(Request $request): Response
    {
        $user = $this->getUser();

        return $this->render('dashboard/analytics.html.twig', [
            'user' => $user
        ]);    }


    /**
     * @Route("/resetPassword",name="resetPassword")
     * @return Response
     */

    public function resetPassword(Request $request): Response
    {
        $session = $request->getSession();

        return $this->render('connexion/resetPassword.html.twig', [

        ]);
    }




    /**
     * @Route("/newPassword",name="newPassword")
     * @return Response
     */

    public function newPassword(Request $request): Response
    {
        $session = $request->getSession();

        return $this->render('connexion/newPassword.html.twig', [

        ]);
    }

    //hna salit



}