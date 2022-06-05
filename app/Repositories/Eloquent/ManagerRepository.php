<?php
namespace App\Repositories\Eloquent;

use App\Models\Manager;
use App\Repositories\EloquentRepository;

class ManagerRepository extends EloquentRepository
{
    public function model()
    {
        return Manager::class;
    }
}
