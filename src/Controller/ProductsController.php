<?php

namespace App\Controller;

use App\Entity\AddProductHistory;
use App\Entity\Products;
use App\Form\AddStockType;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/editor/products')]
final class ProductsController extends AbstractController
{
    #[Route(name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, 
        EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            
            if ($image) {
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $imageSafeName = $slugger->slug($originalName);
                $newFileName = $imageSafeName.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move($this->getParameter('image_products_dir'), $newFileName);
                } catch (FileException $fileException) {
                    //throw $th;
                }
                $product->setImage($newFileName);
            }

            
            $entityManager->persist($product);
            $entityManager->flush();

            $productHistory = new AddProductHistory();
            $productHistory->setQuantity($product->getStock());
            $productHistory->setProduct($product);

            $entityManager->persist($productHistory);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre produit a été ajouté');
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }   

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre produit a été modifié');

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('danger', 'Votre produit a été supprimé');
        }

        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add/stock/{productId}', name: 'app_add_stock', methods: ['GET', 'POST'])]
    public function addStock($productId, 
        ProductsRepository $productRepo,  
        Request $request, EntityManagerInterface $em)
    {
     
        $product = $productRepo->find($productId);
        $addProductHistory = new AddProductHistory();
        $form = $this->createForm(AddStockType::class, $addProductHistory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($addProductHistory->getQuantity() > 0){
                $newQty = $product->getStock() + $addProductHistory->getQuantity();
                $product->setStock($newQty);

                $em->persist($addProductHistory);
                $em->flush();
                $this->addFlash('success', 'Le stock de votre produit a été modifié avec succès.');

                return $this->redirectToRoute('app_products_index');
            }
            else {
                $this->addFlash('danger', 'Le stock ne doit pas $etre inferieur à 0');
            }
        } 

        $em->flush();

        return $this->render('products/addStock.html.twig', [
            'form' => $form->createView()
        ]);
    }
}