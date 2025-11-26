<?php

namespace App\DataFixtures;

use App\Entity\Responsable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ResponsableFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $responsables = [
            'Antoine Dupont',
            'Grégory Aldrit',
            'Damien Penaud',
            'Julien Marchand',
        ];

        foreach ($responsables as $nom) {
            $responsable = new Responsable();
            $responsable->setNom($nom);
            $manager->persist($responsable);
            $this->addReference($nom, $responsable);
        }

        $manager->flush();
    }
}
