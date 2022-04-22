<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/liste-des-categories", name="category_list")
     */
    public function list(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categorie/{slug}", name="category_show")
     */
    public function show(CategoryRepository $repo, string $slug): Response
    {
        $category = $repo->findOneBy(['slug' => $slug]);

        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/admin/supprimer-categorie/{slug}", name="category_delete")
     */
    public function delete(EntityManagerInterface $em, Category $cat): Response
    {
        $em->remove($cat);
        try{
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée.');
        }catch(Exception $e){
            $this->addFlash('danger', 'Echec lors de la suppression de la catégorie.');
        }

        return $this->redirectToRoute("category_list");
    }

     /**
     * @Route("/admin/nouvelle-categorie", name="category_new")
     */
    public function new(EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugger->slug($category->getName().'-'.rand(100, 500));
            $category->setSlug($slug);

            $em->persist($category);

            try{
                $em->flush();
                $this->addFlash('success', 'Catégorie créée.');
            }catch(Exception $e){
                $this->addFlash('danger', 'Echec de la création de la catégorie.');

                return $this->redirectToRoute('category_new');
            }
            
            return $this->redirectToRoute('category_show', array('slug' => $slug));
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modifier-categorie/{slug}", name="category_edit")
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category->setSlug($category->getName().'-'.rand(100,500));
            try{
                $em->flush();
                $this->addFlash('success', 'Catégorie modifiée.');
            }catch(Exception $e){
                $this->addFlash('danger', 'Echec lors de la modification de la catégorie.');
            }

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
