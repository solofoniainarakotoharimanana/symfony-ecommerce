<?php 

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=1; $i <=30 ; $i++) { 
            $product = new Products;
            $product->setName($faker->word().$i);
            $product->setDescription(mt_rand(0,1) === 0 ? $faker->text() : "" );
            $product->setImage(mt_rand(0,1) ? $faker->image(null, 120, 120) : "");
            $product->setPrice(mt_rand(10, 500));
            $product->setStock(mt_rand(0, 200));

            $manager->persist($product);
        }
        $manager->flush();
    }
}