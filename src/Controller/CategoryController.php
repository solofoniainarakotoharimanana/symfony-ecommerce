<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_category')]
    public function index(CategoryRepository $cateRepo, Request $request): Response
    {

        return $this->render('category/list.html.twig', [
            'categories' => $cateRepo->findAll()
        ]);
    }

    #[Route('/category/new', name: 'app_category_new')]
    public function newCategory(
        EntityManagerInterface $em,
        Request $request
        ):Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category->setCreatedAt(new \DateTimeImmutable());
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Votre catégorie a été bien créee avec succès.');

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/newOrUpdate.html.twig', [
            'form' => $form->createView(),
            'operation' => 'new'
        ]);
    }

    #[Route('/category/{id}/update', name: 'app_category_update')]
    public function updateCategory(Category $category, 
        Request $request, 
        EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', 'Votre catégorie a été bien modifié avec succès.');

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/newOrUpdate.html.twig', [
            'category' => $category,
            'form' => $form->createView(), 
            'operation' => 'update'
        ]);
    }

    #[Route('/category/{id}/delete', name: 'app_category_delete')]
    public function deleteCategory(Category $category, EntityManagerInterface $em, Request $request): Response
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('danger', 'Le catégorie '.$category->getName().' a été supprimée avec succès.');

        return $this->redirectToRoute('app_category');
    }
}
