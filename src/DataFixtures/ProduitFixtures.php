<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Produit;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');
       
        for($i=0; $i<20; $i++){ $produit = new Produit();
            $produit->setNom($faker->word())
                ->setPrix($faker->randomNumber($nbDigits=null,$strict=false))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setDescription($faker->paragraph($nbSentences = 1, $variableNbSentences = true))
                ->setStock($faker->boolean($chanceOfGettingTrue = 50));
                $manager->persist($produit);

            for($j=0;$j<5;$j++){
                $category = new Category();
                $category->setNom($faker->safeColorName)
->setDescription($faker->paragraph($nbSentences = 1, $variableNbSentences = true));

                    $category->addProduit($produit);
                $manager->persist($category);
                    }
        }
        $manager->flush();
    }
}
