<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CrearUsuario extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        $user=User::create([
            "email"=>"Admin@jamb.com",
            "name"=>"AdministradorJamb",
            "password"=>bcrypt("admin123")
        ]);
        $user->assignRole('admin');
    }
}
