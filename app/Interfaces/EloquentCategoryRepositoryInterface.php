<?php

namespace App\Interfaces;
use App\Interfaces\RepositoryInterface;

interface EloquentCategoryRepositoryInterface extends RepositoryInterface
{
    public function findBySlug($slug);
}