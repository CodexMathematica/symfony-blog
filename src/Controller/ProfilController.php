<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\Upload;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profile/{slug}", name="profil_show")
     */
    public function show(UserRepository $repo, $slug): Response
    {
        $user = $repo->findOneBy(['slug' => $slug]);

        return $this->render('profil/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/profile/modification/{slug}", name="profil_edit")
     */
    public function edit(Request $request, User $user, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder, Upload $fileUploader): Response
    {
        $oldAvatar = $user->getAvatar();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile->getClientOriginalName()) {
                if($avatarFile !== 'default.png'){
                    $fileUploader->fileDelete($oldAvatar);
                }
                $avatarFileName = $fileUploader->upload($avatarFile);
                $user->setAvatar($avatarFileName);
            }
            $em->flush();
            
            return $this->redirectToRoute("profil_show", ['slug' => $user->getSlug()]);
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
