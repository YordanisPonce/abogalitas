<?php

namespace App\Repositories;

use App\Interfaces\EloquentDocumentRepositoryInterface;
use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EloquentDocumentRepository implements EloquentDocumentRepositoryInterface
{

  private $with = ['items'];
  public function __construct(private readonly Document $model)
  {
  }

  public function findAll($options = [])
  {

    $query = $this->model->newQuery()->with($this->with);

    if ($options['user_id']) {
      $query->where('user_id', $options['user_id']);
    }



    if ($options['paginate']) {

      return $query->paginate(config('app.pagination_items'));
    }
    return $query->get();

  }

  public function findById($id)
  {
    return $this->model->newQuery()->with($this->with)->find($id);
  }

  public function save(array $attributes)
  {
    return $this->model->newQuery()->create($attributes);
  }

  public function update(array $attributes, $id)
  {
    return $this->findById($id)->fill($attributes)->save();
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

