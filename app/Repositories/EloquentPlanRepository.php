<?php

namespace App\Repositories;

use App\Interfaces\EloquentPlanRepositoryInterface;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EloquentPlanRepository implements EloquentPlanRepositoryInterface
{
  private array $with = ['features', 'plan'];
  public function __construct(private readonly Plan $model)
  {
  }

  public function findAll($options = [])
  {
    return $this->model->with($this->with)->newQuery()->get();
  }

  public function findById($id)
  {
    return $this->model->newQuery()->find($id);
  }

  public function save(array $attributes)
  {

    if (isset($attributes['features'])) {
      unset($attributes['features']);
    }
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

}