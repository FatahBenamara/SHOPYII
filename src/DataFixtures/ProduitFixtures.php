<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Produit;

class ProduitFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        
        $this->createMany(10, "produit", function ($num) {
            //Création d'un utilisateur 

                        $produit = (new Produit)
                        ->setCategorie($this->faker->words($nb = 3, $asText = true))
                        ->setStock($this->faker->randomNumber(2))
                        ->setPrix($this->faker->randomNumber(3))
                        ->setPhoto($this->faker->numberBetween($min=1, $max= 5) . ".jpg")
                        ->setPublic($this ->faker->randomElement(["h", "f", "a"]))
                        ->setTaille($this ->faker->randomElement(["XL", "XXL", "M"]))
                        ->setCouleur($this->faker->colorName)
                        ->setDescription($this->faker->sentences($nb = 1, $variableNbSentences = true))
                        ->setTitre("titre" .$num)
                        ->setReference($this->faker->word);

            return $produit;
            
        });

        $manager->flush();
    }
}
