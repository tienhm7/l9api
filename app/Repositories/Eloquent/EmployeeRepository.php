<?php
namespace App\Repositories\Eloquent;

use App\Models\Employee;
use App\Repositories\EloquentRepository;

class EmployeeRepository extends EloquentRepository
{
    public function model()
    {
        return Employee::class;
    }
}
