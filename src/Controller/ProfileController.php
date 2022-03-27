<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\ReviewRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function __construct(
        private ReviewRepository $reviewRepository,
    ) {}

    #[IsGranted('ROLE_USER')]
    #[Route(
        '/profile/{user}',
        name: 'app_profile',
        requirements: ['user' => '\d+']
    )]
    public function profile(User $user): Response
    {
        $reviews = $this->reviewRepository->getUserReviews($user->getId());

        return $this->render('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'reviews' => $reviews,
        ]);
    }
}
