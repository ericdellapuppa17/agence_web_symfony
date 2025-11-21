<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- Un utilisateur ADMIN ---
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);

        $adminPassword = $this->hasher->hashPassword($admin, 'admin1234');
        $admin->setPassword($adminPassword);

        $manager->persist($admin);

        // --- Un utilisateur AGENT ---
        $agent = new User();
        $agent->setEmail('agent@example.com');
        $agent->setRoles(['ROLE_AGENT']);

        $agentPassword = $this->hasher->hashPassword($agent, 'agent1234');
        $agent->setPassword($agentPassword);

        $manager->persist($agent);

        // Mise en DB
        $manager->flush();
    }
}
