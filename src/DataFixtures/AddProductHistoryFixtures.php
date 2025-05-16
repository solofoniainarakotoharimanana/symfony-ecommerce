<?php

namespace App\DataFixtures;

use App\Entity\AddProductHistory as EntityAddProductHistory;
use App\Repository\ProductsRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AddProductHistory extends Fixture
{

    public function __construct(private ProductsRepository $productRepo)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
       
        $faker = Factory::create('fr_FR');
        $products = $this->productRepo->findAll();

        for ($i=1; $i <=25 ; $i++) { 
            $addProductHistory = new AddProductHistory();
            $addProductHistory->setQuantity(mt_rand(0, 200));
            
            $addProductHistory->setProduct($products[mt_rand(0, count($products) - 1)]);
            $manager->persist($addProductHistory);
        }

        $manager->flush();
        
        
    }

    public function getDependencies()
    {
        return [ProductFixtures::class];
    }
}