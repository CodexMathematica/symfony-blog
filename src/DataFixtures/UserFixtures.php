<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{

    private $encoder;
    private $slugger;

    public function __construct(UserPasswordHasherInterface $encoder, SluggerInterface $slugger)
    {
        $this->encoder = $encoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@admin.fr')
        ->setName('Jacky l\'admin')
        ->setSlug($this->slugger->slug($admin->getName().'-'.rand(100, 999))->lower())
        ->setAvatar('default.png')
        ->setRoles(['ROLE_ADMIN'])
        ->setPassword($this->encoder->hashPassword($admin, 'adminadmin'))
        ;
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@user.fr')
        ->setName('Jacky')
        ->setAvatar('default.png')
        ->setSlug($this->slugger->slug($user->getName().'-'.rand(100, 999))->lower())
        ->setRoles(['ROLE_USER'])
        ->setPassword($this->encoder->hashPassword($user, 'useruser'))
        ;
        $manager->persist($user);

        $manager->flush();
    }
}
