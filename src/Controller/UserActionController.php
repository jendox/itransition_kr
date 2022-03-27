<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Review;
use App\Entity\UserRating;
use App\Repository\UserRatingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserActionController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(
        '/review/rate/{id}',
        name: 'app_rate_review',
        requirements: ['review' => '\d+'],
        methods: ['post']
    )]
    public function rate(Review $id, Request $request, UserRatingRepository $userRatingRepository): Response
    {
        $value = $request->get('rating');
        $userRating = new UserRating($this->getUser()->getId(), $id, (int)$value);
        if ($userRatingRepository->findOneBy([
            'userId' => $userRating->getUserId(),
            'review' => $userRating->getReview()])) {
            $userRatingRepository->update($userRating);
        } else {
            $userRatingRepository->add($userRating);
        }
        return $this->json("ok");
    }
}