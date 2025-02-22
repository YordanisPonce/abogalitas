<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Interfaces\EloquentCategoryRepositoryInterface;
use App\Interfaces\RepositoryInterface;

class CategoryService
{
    public function __construct(private readonly EloquentCategoryRepositoryInterface $repository)
    {
    }

    public function findAll()
    {
        return ResponseHelper::ok("Todos las categorías", $this->repository->findAll());
    }

    public function findById($id)
    {
        return ResponseHelper::ok("Categoría por id", $this->repository->findById($id));
    }
    public function findBySlug($slug)
    {
        $model = $this->repository->findBySlug($slug);
        throw_if(!$model, "No se encuentra categoría con el slug proporcionado");
        return ResponseHelper::ok("Categoría por id", $model);
    }

    public function save(array $attributes)
    {
        $model = $this->repository->save($attributes);
        return ResponseHelper::ok("Categoría insertada satisfactoriamente", $this->repository->findById($model->id));
    }

    public function update(array $attributes, $id)
    {
        $model = $this->repository->findById($id);
        throw_if(!$model, "No se encuentra categoría con el identificador proporcionado");
        $model->update($attributes);
        return ResponseHelper::ok("Categoría actualizada satisfactoriamente", $this->repository->findById($id));
    }

    public function delete($id)
    {
        $success = $this->repository->delete($id);
        throw_if(!$success, "No se encuentra categoría con el identificador proporcionado");
        return ResponseHelper::ok("Categoría eliminada satisfactoriamente");
    }
}