<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route(
        '/settings/{user}',
        name: 'app_settings',
        requirements: ['id' => '\d+'],
    )]
    public function settings(User $user): Response
    {
        return $this->render('settings/settings.html.twig');
    }
}