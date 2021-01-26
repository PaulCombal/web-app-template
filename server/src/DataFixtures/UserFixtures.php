<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = [
            [
                'f' => 'Paul',
                'l' => 'Bizmuth'
            ],
            [
                'f' => 'Jean',
                'l' => 'Doe'
            ]
        ];

        foreach ($names as $name) {
            $user = new User();
            $user->setEmail($name['f'].'.'.$name['l'].'@yolo.com');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
