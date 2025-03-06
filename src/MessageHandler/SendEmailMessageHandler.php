<?php

namespace App\MessageHandler;

use App\Entity\Product;
use Psr\Log\LoggerInterface;
use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Doctrine\ORM\EntityManagerInterface;

#[AsMessageHandler]
final class SendEmailMessageHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }
    public function __invoke(SendEmailMessage $message): void
    {
        $product = new Product();
        $product->setName($message->getName());
        $product->setPrice($message->getPrice());
        $product->setQuatity($message->getQuatity());
        $this->entityManager->persist($product);
        // $this->entityManager->flush();
    }
}
