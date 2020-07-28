<?php

declare (strict_types = 1);

namespace App\Service;

class Service extends BaseService
{
    protected $criteria = [];

    public function __construct()
    {
        # code...
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }
}
