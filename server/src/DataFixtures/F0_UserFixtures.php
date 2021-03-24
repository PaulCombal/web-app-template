<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class F0_UserFixtures extends Fixture
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

        # Load the google subs
        try {
            $handle = fopen(__DIR__ . "/google_subs.txt", "r");
            if ($handle) {
                while (($sub = fgets($handle, 4096)) !== false) {
                    $user = new User();
                    $user->setGoogleSub($sub);
                    $user->setEmail($sub.'@gmail.com');
                    $user->setRoles(['ROLE_ADMIN', 'ROLE_OWNER']);
                    $manager->persist($user);
                }
                if (!feof($handle)) {
                    echo "Erreur: fgets() a échoué\n";
                }
                fclose($handle);
            }
        } catch (\Exception $e) {
            echo "No google subs to load: " . $e->getMessage();
        }

        $manager->flush();
    }
}
