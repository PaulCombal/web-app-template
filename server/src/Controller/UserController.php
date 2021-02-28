<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1")
 *
 * Class ProfileController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/user/home", name="user_home", methods={"GET"})
     */
    public function getCoolStats()
    {
        return $this->json([]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/user", name="user_listing", methods={"GET"})
     */
    public function getListing(UserRepository $userRepository)
    {
        return $this->json($userRepository->formattedListing(), context: ['groups' => ['admin']]);
    }
}
