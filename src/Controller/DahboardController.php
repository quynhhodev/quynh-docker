<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\SendEmailMessage;

final class DahboardController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {
    }

    #[Route('/dahboard/abc', name: 'app_dahboard')]
    public function index(): JsonResponse
    {
        $this->bus->dispatch(new SendEmailMessage('abc', 500, 100));

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DahboardController.php',
        ]);
    }
}
