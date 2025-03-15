<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       Role::create(['name' => 'Administrator']);
        Role::create(['name' => 'Store User']);
        $adminRole = Role::where('name', 'Administrator')->first();

        User::create([
            'name' => 'anis',
            'email' => 'anis@example.com',
            'password' => Hash::make('0668770907'), // Change ce mot de passe en production
            'role_id' => $adminRole->id,
            'active' => true,
        ]);
    }
}
