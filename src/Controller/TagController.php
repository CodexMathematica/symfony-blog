<?php

namespace App\Controller;

use Exception;
use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    /**
     * @Route("/badges", name="tag_list")
     */
    public function index(TagRepository $repo): Response
    {
        $tags = $repo->findAll();

        return $this->render('tag/list.html.twig', [
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/creation-de-badges", name="tag_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
       $tag = new Tag();
       $form = $this->createForm(TagType::class, $tag); 

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
           $em->persist($tag);

           try{
               $em->flush();
               $this->addFlash('success', 'Badge créé.');
           }catch(Exception $e){
            $this->addFlash('danger', 'Echec lors de la création du badge.');

               return $this->redirectToRoute('tag_new');
           }

           return $this->redirectToRoute('tag_list');
       }

        return $this->render('tag/new.html.twig', [
            'form' => $form->createView() 
        ]);
    }

    /**
     * @Route("/modifier-badge/{id}", name="tag_edit")
     */
    public function edit(Request $request, Tag $tag, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $em->flush();
                $this->addFlash('success', 'Badge modifiée.');
            }catch(Exception $e){
                $this->addFlash('danger', 'Echec lors de la modification du badge.');

                return $this->redirectToRoute('tag_edit');
            }

            return $this->redirectToRoute('tag_list');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView() 
        ]);
    }

        /**
     * @Route("/supprimer-badge/{id}", name="tag_delete")
     */
    public function delete(Tag $tag, EntityManagerInterface $em): Response
    {
        $em->remove($tag);
        try{
            $em->flush();
            $this->addFlash('success', 'Badge supprimé.');
        }catch(Exception $e){
            $this->addFlash('danger', 'Echec de la suppression du badge.');
        }
        
        return $this->redirectToRoute("tag_list");
    }
}
