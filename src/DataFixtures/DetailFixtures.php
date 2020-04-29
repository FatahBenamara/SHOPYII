<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface as Dependent;
use App\Entity\Detail;

class DetailFixtures extends BaseFixture implements Dependent
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(10, "detail", function($num){
                        $detail = (new Detail) 
                                                    
                            ->setProduit($this->getRandomReference("produit")) 
                            ->setCommande($this->getRandomReference("commande")) 
                            ->setPrix($this->faker->randomNumber(3))
                            ->setQuantite($this->faker->randomNumber(2));
                                                    

                            return $detail;
        });
        $manager->flush();
    }

    public function getDependencies(){
        // j'indique les fixtures qui doivent être lancées avant la fixture actuelle
        return [ ProduitFixtures::class , CommandeFixtures::class];
    }

}
