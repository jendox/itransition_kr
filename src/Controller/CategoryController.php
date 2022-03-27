<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Category;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    public function __construct(
        private ReviewRepository $reviewRepository,
    ){}

    #[Route(
        '/category/{id}',
        name: 'app_category',
        requirements: ['id' => '[1-4]'],
    )]
    public function index(Category $id): Response
    {
        $categoryReviews = $this->reviewRepository->findBy(['category' => $id]);
        $categoryName = strtolower($id->getName());
        return $this->render('category/category.html.twig', [
            'categoryName' => $categoryName,
            'categoryReviews' =>$categoryReviews,
        ]);
    }
}