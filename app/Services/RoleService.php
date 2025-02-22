<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Interfaces\EloquentRoleRepositoryInterface;
use App\Interfaces\RepositoryInterface;

class RoleService
{
    public function __construct(private readonly EloquentRoleRepositoryInterface $repository)
    {
    }

    public function findAll()
    {

    }

    public function findById($id)
    {

    }

    public function save(array $attributes)
    {
        return ResponseHelper::ok("Rol creado satisfactoriamente", $this->repository->save($attributes));
    }

    public function update(array $attributes, $id)
    {

    }

    public function delete($id)
    {

    }


    public function findByName($name)
    {
        $role = $this->repository->query()->where('name', $name)->first();
        throw_if(!$role, "Rol no encontrado");
        return ResponseHelper::ok("Rol encontrado satisfactoriamente", $role);
    }
}