<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Membre;

class MembreFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        //Création de l'utilisateur ADMIN sans les fakers
        $membre = new Membre;

        $membre->setEmail("admin@boutique.com");
        $membre->setPassword(password_hash("admin", PASSWORD_DEFAULT));
        $membre->setNom("Min");
        $membre->setPrenom("Ad");
        $membre->setAdresse("RueBelleRue");
        $membre->setCodePostal("06100");
        $membre->setVille("Paris");
        $membre->setRole("ROLE_ADMIN");
        $membre->setPseudo("Admin");
        $membre->setCivilite("h");

        $manager->persist($membre);

            $this->createMany(10, "membre", function ($num) {
            //Création d'un utilisateur 

                $membre = (new Membre)
                                ->setEmail("membre" . $num . "@yopmail.com")
                                ->setPassword(password_hash("membre" . $num, PASSWORD_DEFAULT))
                                ->setNom($this->faker->lastName)
                                ->setPrenom($this->faker->FirstName)
                                ->setAdresse($this->faker->Address)
                                ->setCodePostal(substr($this->faker->postcode, 0, 5))
                                ->setVille(substr($this->faker->City, 0, 20))
                                ->setRole("ROLE_USER")
                                ->setPseudo("membre" . $num)
                                ->setCivilite($this->faker->randomElement(["H", "F", "A"]));

                return $membre;
        });

        $manager->flush();
    }
}
