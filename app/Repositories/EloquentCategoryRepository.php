<?php

namespace App\Repositories;

use App\Interfaces\EloquentCategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EloquentCategoryRepository implements EloquentCategoryRepositoryInterface
{

  public function __construct(private readonly Category $model)
  {
  }

  public function findAll($options = [])
  {
    return $this->model->newQuery()->get();
  }

  public function findById($id)
  {
    return $this->model->newQuery()->find($id);
  }

  public function save(array $attributes)
  {
    return $this->model->newQuery()->create($attributes);
  }

  public function update(array $attributes, $id)
  {
    return $this->findById($id)->update($attributes);
  }

  public function delete($id)
  {
    return $this->model->destroy($id);
  }

  public function query(): Builder
  {
    return $this->model->newQuery();
  }

  public function findBySlug($slug)
  {
    return $this->query()->where('slug', $slug)->first();
  }
}