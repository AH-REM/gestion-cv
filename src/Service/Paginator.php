<?php

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;

class Paginator
{

    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function paginate($query, $request)
    {
        return $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /* Page par défaut */
            15 /* Resultat maximum */
        );
    }

}
