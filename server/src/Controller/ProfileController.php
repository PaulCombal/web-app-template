<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1")
 *
 * Class ProfileController
 * @package App\Controller
 */
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'my_profile', methods: ['GET'])]
    public function myProfile()
    {
        return $this->json((array)$this->getUser());
    }
}
