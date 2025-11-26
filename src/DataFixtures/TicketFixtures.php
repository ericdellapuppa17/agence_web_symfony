<?php

namespace App\DataFixtures;

use App\Entity\Ticket;
use App\Entity\Categorie;
use App\Entity\Statut;
use App\Entity\Responsable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TicketFixtures extends Fixture implements DependentFixtureInterface
{        
    public function load(ObjectManager $manager): void
    {        
        $ticket1 = new Ticket();
        $ticket1->setAuteur("client1@example.com");
        // date ouverture auto par entité
        // pas de date de clôture
        $ticket1->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec facilisis tortor.");
        $ticket1->setCategorie($this->getReference('Panne', Categorie::class));
        $ticket1->setStatut($this->getReference('Nouveau', Statut::class));
        // pas de responsable
        $manager->persist($ticket1);

        $ticket2 = new Ticket();
        $ticket2->setAuteur("client2@example.com");
        // date ouverture auto par entité
        // pas de date de clôture
        $ticket2->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec facilisis tortor.");
        $ticket2->setCategorie($this->getReference('Incident', Categorie::class));
        $ticket2->setStatut($this->getReference('Ouvert', Statut::class));
        $ticket2->setResponsable($this->getReference('Antoine Dupont', Responsable::class));
        $manager->persist($ticket2);

        $ticket3 = new Ticket();
        $ticket3->setAuteur("client3@example.com");
        // date ouverture auto par entité
        $ticket3->setDateCloture(new \DateTime("2025-12-31 14:30:00"));
        $ticket3->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec facilisis tortor.");
        $ticket3->setCategorie($this->getReference('Incident', Categorie::class));
        $ticket3->setStatut($this->getReference('Fermé', Statut::class));
        $ticket3->setResponsable($this->getReference('Grégory Aldrit', Responsable::class));
        $manager->persist($ticket3);

        $ticket4 = new Ticket();
        $ticket4->setAuteur("client4@example.com");
        // date ouverture auto par entité
        // pas de date de clôture
        $ticket4->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec facilisis tortor.");
        $ticket4->setCategorie($this->getReference('Anomalie', Categorie::class));
        $ticket4->setStatut($this->getReference('Ouvert', Statut::class));
        $ticket4->setResponsable($this->getReference('Julien Marchand', Responsable::class));
        $manager->persist($ticket4);

        $ticket5 = new Ticket();
        $ticket5->setAuteur("client5@example.com");
        // date ouverture auto par entité
        // pas de date de clôture
        $ticket5->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec facilisis tortor.");
        $ticket5->setCategorie($this->getReference('Information', Categorie::class));
        $ticket5->setStatut($this->getReference('Résolu', Statut::class));
        $ticket5->setResponsable($this->getReference('Julien Marchand', Responsable::class));
        $manager->persist($ticket5);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategorieFixtures::class,
            StatutFixture::class,
            ResponsableFixture::class,
        ];
    }

}

