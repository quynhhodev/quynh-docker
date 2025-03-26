<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\SendEmailMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;

final class DahboardController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly Security $security
    ) {
    }

    #[Route('/dahboard/abc', name: 'app_dahboard')]
    public function index(UserPasswordHasherInterface $passwordHasher, 
    JWTTokenManagerInterface $JWTManager,
    AuthenticationSuccessHandler $authenticationSuccessHandler,
    Request $request
    ): JsonResponse
    {
        $this->bus->dispatch(new SendEmailMessage('abc', 500, 100));
        $user = $this->userRepository->findOneBy(['id' => 1]);
        $token = $this->getTokenUser($user, $JWTManager);
        // dd($token->getContent());
        return $this->json([
            json_decode($token->getContent(), true)
        ]);
    }

    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        // ...

        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }

    
}
