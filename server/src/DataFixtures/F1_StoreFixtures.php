<?php

namespace App\DataFixtures;

use App\Entity\Store;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class F1_StoreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $store = new Store();
        $store->setFullName('Feukit official store');
        $store->setDisplayName('Feukit');

        $admins = $manager->getRepository(User::class)->getAdmins();

        foreach ($admins as $admin) {
            $store->addOwner($admin);
        }

        $manager->persist($store);
        $manager->flush();
    }
}
