<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $client = (new Client())
                ->setPrenom("prenom$i")
                ->setNom("Nom$i")
                ->setEmail("user$i@domain.fr")
                ->setDateCreation(new \DateTime());
            $manager->persist($client);
        }
        $manager->flush();
    }
}
