<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private ReviewRepository $reviewRepository,
    ){}

    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        $latestReviews = $this->reviewRepository->getRecentlyAdded();
        return $this->render('homepage/homepage.html.twig', [
            'latestReviews' => $latestReviews,
        ]);
    }
}
