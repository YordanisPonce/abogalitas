<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function __construct(private readonly RoleService $roleService)
    {

    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role = $this->roleService->findByName(RoleEnum::ADMIN->value)['data'];

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'adminadmin',
            'email_verified_at' => now(),
            'role_id' => $role->id
        ]);
    }
}
