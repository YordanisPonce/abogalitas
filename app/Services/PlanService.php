<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Interfaces\EloquentPlanRepositoryInterface;
use App\Interfaces\RepositoryInterface;

class PlanService
{
    public function __construct(private readonly EloquentPlanRepositoryInterface $repository)
    {
    }

    public function findAll()
    {
        $plans = $this->repository->findAll();
        return ResponseHelper::ok("Todos los planes", $plans);
    }

    public function findById($id)
    {
        $plan = $this->repository->findById($id);
        return ResponseHelper::ok("Plan por id", $plan);
    }

    public function save(array $attributes)
    {
        $plan = $this->repository->save($attributes);

        if ($attributes['features']) {
            $plan->features()->createMany($attributes['features']);
        }

        return ResponseHelper::ok("Plan creado satisfactoriamente", $this->repository->findById($plan->id));
    }

    public function update(array $attributes, $id)
    {
        $plan = $this->repository->findById($id);

        throw_if(!$plan, "No existe plan con el identificador proporcionado");

        if ($attributes['features']) {

            $ids = collect($attributes['features'])->whereNotNull('id')->pluck('id')->toArray();
            $plan->features()->whereNotIn('id', $ids)->delete();

            $features = collect($attributes['features'])->whereNull('id')->toArray();
            $plan->features()->createMany($features);
        }

        return ResponseHelper::ok("Plan actualizado satisfactoriamente", $this->repository->findById($plan->id));
    }

    public function delete($id)
    {
        $plan = $this->repository->findById($id);
        throw_if(!$plan, "No existe plan con el identificador proporcionado");
        $plan->delete();
        return ResponseHelper::ok("Plan eliminado satisfactoriamente");
    }
}