<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PromotionProduct\DiscountInterface;

final class CalculaterController extends AbstractController
{
    


    public function __construct(
        private DiscountInterface $discount
    )
    {
    }
    #[Route('/calculater', name: 'app_calculater')]
    public function index(): Response
    {
        $product = ['price' => 10000, 'quantity' => 15];

        $productPrice = $this->discount->apply($product['price'], $product['quantity']);

        dd($productPrice);

        return $this->render('calculater/index.html.twig', [
            'controller_name' => 'CalculaterController',
        ]);
    }
}
