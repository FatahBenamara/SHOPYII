<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Commande;
use Doctrine\Common\DataFixtures\DependentFixtureInterface as Dependent;

class CommandeFixtures extends BaseFixture implements Dependent
{
    

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(20, "commande", function($num){
            $commande = (new Commande)
                            ->setMontant($this->faker->randomNumber(3))
                            ->setDateEnregistrement($this->faker->dateTime())
                            ->setEtat($this->faker->randomElement(["en cours", "en attente", "livrée"]))
                            ->setMembre($this->getRandomReference("membre"));
            return $commande;
        });
        $manager->flush();
    }

    public function getDependencies(){
        // j'indique les fixtures qui doivent être lancées avant la fixture actuelle
        return [ MembreFixtures::class ];
    }













    
}
