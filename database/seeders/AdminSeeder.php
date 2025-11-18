<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador si no existe
        if (!User::where('email', 'admin@sigrab.com')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@sigrab.com',
                'dni' => '00000000',
                'tipo_usuario' => 'admin',
                'password' => Hash::make('admin123'), // Cambiar después del primer login
            ]);

            echo "✅ Usuario Administrador creado exitosamente\n";
            echo "📧 Email: admin@sigrab.com\n";
            echo "🔑 Contraseña: admin123\n";
            echo "⚠️  IMPORTANTE: Cambia la contraseña después del primer login\n";
        } else {
            echo "ℹ️  Usuario Administrador ya existe\n";
        }
    }
}
