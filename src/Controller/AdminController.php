<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    #[Route(
        '/admin',
        name: 'app_admin'
    )]
    public function admin(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('admin/admin.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(
        '/block/{user}',
        name: 'app_block_user',
        requirements: ['user' => '\d+'],
        methods: ['post']
    )]
    public function blockUser(User $user): Response
    {
        return $this->json(
            [
                "user" => $user->getEmail(),
                "status" => "blocked"
            ]);
    }

    #[Route(
        '/unblock/{user}',
        name: 'app_unblock_user',
        requirements: ['user' => '\d+'],
        methods: ['post']
    )]
    public function unblockUser(User $user): Response
    {
        return $this->json(
            [
                "user" => $user->getEmail(),
                "status" => "active"
            ]);
    }
}