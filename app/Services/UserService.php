<?php

namespace App\Services;

use App\Repositories\Eloquent\UserRepository;

class UserService
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param $attribute
     * @return false|mixed
     */
    public function store($attribute): mixed
    {
        return $this->userRepo->create($attribute);
    }
}
