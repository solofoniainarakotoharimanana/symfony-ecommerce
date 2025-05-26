<?php

namespace App\Controller;

use App\Class\SearchProduct;
use App\Entity\Products;
use App\Entity\SubCategory;
use App\Form\SearchProductForm;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods:['GET'])]
    public function index(ProductsRepository $productsRepo, CategoryRepository $categoryRepo, Request $request): Response
    {
        $searchProduct = new SearchProduct();
        $searchForm = $this->createForm(SearchProductForm::class, $searchProduct);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $products = $productsRepo->findBySearch($searchProduct);

            return $this->render('home/index.html.twig', [
                'searchForm' => $searchForm->createView(),
                'products' => $products,
                'categories' => $categoryRepo->findAll()
            ]);
        }

        return $this->render('home/index.html.twig', [
            'products' => $productsRepo->findBy([], ['id' => 'DESC']),
            'searchForm' => $searchForm->createView(),
            'categories' => $categoryRepo->findAll()
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

    #[Route('/product/subcategory/{id}/filter', name:'app_filter_product', methods: ['GET'])]
    public function filterProduct(SubCategory $subCategory, 
        SubCategoryRepository $subCategoryRepo, 
        Request $request, 
        ProductsRepository $productsRepo,
        CategoryRepository $categoryRepo
        ):Response
    {
        
        $products = $subCategory->getProducts()->getValues();
        
        $searchProduct = new SearchProduct();
        $searchForm = $this->createForm(SearchProductForm::class, $searchProduct);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $products = $productsRepo->findBySearch($searchProduct);

            return $this->render('home/index.html.twig', [
                'searchForm' => $searchForm->createView(),
                'products' => $products,
                'categories' => $categoryRepo->findAll()
            ]);
        }

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'searchForm' => $searchForm->createView(),
            'categories' => $categoryRepo->findAll()
        ]);

    }
}