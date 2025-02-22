<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface RepositoryInterface
{
    public function findAll(array $options = []);

    public function findById($id);

    public function save(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);
    public function query(): Builder;
}
