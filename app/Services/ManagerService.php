<?php

namespace App\Services;

use App\Repositories\Eloquent\ManagerRepository;

class ManagerService
{
    protected ManagerRepository $managerRepo;

    public function __construct(ManagerRepository $managerRepo)
    {
        $this->managerRepo = $managerRepo;
    }

    /**
     * @param $attribute
     * @return false|mixed
     */
    public function store($attribute): mixed
    {
        return $this->managerRepo->create($attribute);
    }
}
