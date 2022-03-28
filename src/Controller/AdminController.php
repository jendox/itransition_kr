<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
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
        '/change_state/{user}',
        name: 'app_change_user_state',
        requirements: ['user' => '\d+'],
        methods: ['post']
    )]
    public function change(User $user, Request $request): Response
    {
        $state = true;
        if ($request->get('action') == 'block') {
            $state = false;
        }
        $user->setIsActive($state);
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        catch (Exception $e)
        {
            return $this->json(
                [
                    "status" => "error"
                ]);
        }
        return $this->json(
            [
                "status" => "ok",
                "userId" => $user->getId(),
                "userState" => $user->getIsActive()
            ]);
    }
}