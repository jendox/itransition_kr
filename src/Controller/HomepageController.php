<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomepageController extends AbstractController
{
    public function __construct(
        private ReviewRepository $reviewRepository
    ){}

    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        $latestReviews = $this->reviewRepository->getRecentlyAdded();
//        $mostRatedReviews = $this->reviewRepository->getMostRated();
        return $this->render('homepage/homepage.html.twig', [
            'latestReviews' => $latestReviews,
//            'mostRatedReviews' => $mostRatedReviews,
        ]);
    }

//    #[Route('/admin')]
//    public function admin(): Response
//    {
//        return new Response("Admin page");
//    }
//
//    #[Route('/profile/{id}', name: 'app_profile')]
//    public function profile(int $id): Response
//    {
//        if ($id == $this->getUser()->getId()) {
//            return new Response("Profile page" . $id);
//        }
//        return new Response("Access denied");
//    }
}
