<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

       
        $faker = Factory::create('fr_FR');

        for ($i=1; $i <= 20 ; $i++) { 
            $user = new User();
            $user->setEmail($faker->email());
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setRoles(mt_rand(0, 1) === 0 ? ['ROLE_ADMIN'] : ['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}