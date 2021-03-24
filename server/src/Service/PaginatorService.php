<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function n($max = 100, $default = 20) {
        $n = ($n = $this->req->query->get('n')) ? intval($n) : $default;
        return min($n, $max);
    }

    public function to($max = 100) {
        return $this->from() + $this->n($max);
    }

    public function limit_query(QueryBuilder $query): QueryBuilder
    {
        return $query
            ->setFirstResult($this->from())
            ->setMaxResults($this->n());
    }

    public function paginate_query(QueryBuilder $query, $fetch_join_columns = false): array
    {
        $lim_q = $this->limit_query($query);
        $paginator = new Paginator($lim_q, $fetch_join_columns);
        $count = count($paginator);
        return [
            'count' => $count,
            'data' => $paginator
        ];
    }
}