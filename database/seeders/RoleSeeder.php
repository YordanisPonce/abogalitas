<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Services\RoleService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    public function __construct(private readonly RoleService $roleService)
    {

    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        foreach (RoleEnum::getValues() as $key => $value) {
            $this->roleService->save([
                'name' => $value,
            ]);
        }
    }
}
