<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@admin.fr')
        ->setRoles(['ROLE_ADMIN'])
        ->setPassword($this->encoder->hashPassword($admin, 'adminadmin'))
        ;
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@user.fr')
        ->setRoles(['ROLE_USER'])
        ->setPassword($this->encoder->hashPassword($user, 'useruser'))
        ;
        $manager->persist($user);

        $manager->flush();
    }
}
