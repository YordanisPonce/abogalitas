<?php

namespace App\Services;
use App\Interfaces\RepositoryInterface;

class UserService
{
    public function __construct(private readonly RepositoryInterface $repository)
    {
    }

    public function findAll(){
        
    }

    public function findById($id){
        
    }

    public function save(array $attributes){
        
    }

    public function update(array $attributes, $id){
        
    }

    public function delete($id){
        
    }
}