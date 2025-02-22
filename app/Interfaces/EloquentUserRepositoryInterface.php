<?php

namespace App\Interfaces;

interface EloquentUserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail($email);
}
