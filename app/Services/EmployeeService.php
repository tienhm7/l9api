<?php

namespace App\Services;

use App\Repositories\Eloquent\EmployeeRepository;

class EmployeeService
{
    protected EmployeeRepository $employeeRepo;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    /**
     * @param $attribute
     * @return false|mixed
     */
    public function store($attribute): mixed
    {
        return $this->employeeRepo->create($attribute);
    }
}
