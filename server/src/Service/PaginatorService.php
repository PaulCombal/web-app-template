<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserLogin;
use App\Entity\UserPreferences;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginatorService
{
    private $req;

    public function __construct(RequestStack $requestStack)
    {
        $this->req = $requestStack->getCurrentRequest();
    }

    public function from() {
        return ($s = $this->req->query->get('s')) ? intval($s) : 0;
    }

    public function n($max = 100) {
        $n = ($n = $this->req->query->get('n')) ? intval($n) : 20;
        return min($n, $max);
    }

    public function to($max = 100) {
        return $this->from() + $this->n($max);
    }
}