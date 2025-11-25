<?php

namespace App\DataFixtures;

use App\Entity\Statut;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatutFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statuts = [
            'Nouveau',
            'Ouvert',
            'Résolu',
            'Fermé',
        ];

        foreach ($statuts as $libelle) {
            $statut = new Statut();
            $statut->setLibelle($libelle);

            $manager->persist($statut);
        }

        $manager->flush();
    }
}
