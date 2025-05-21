<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods:['GET'])]
    public function index(ProductsRepository $productsRepo): Response
    {

        return $this->render('home/index.html.twig', [
            'products' => $productsRepo->findBy([], ['id' => 'DESC'])
        ]);
    }

    #[Route('/product/{id}', name:'app_show_product', methods: ['GET'])]
    public function showProduct(Products $product, ProductsRepository $productsRepo):Response
    {
        $latestProduct = $productsRepo->findBy([], ['id' => 'DESC'], 5);

        return $this->render('home/show.html.twig', [
            'product' => $product,
            'latestProducts' => $latestProduct
        ]);

    }
}