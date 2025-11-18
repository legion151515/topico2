<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActualizarAdminExistenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Actualizar usuario admin@sigram.com a tipo admin
        $usuario = User::where('email', 'admin@sigram.com')->first();

        if ($usuario) {
            $usuario->tipo_usuario = 'admin';
            $usuario->dni = $usuario->dni ?? '00000000';
            $usuario->save();

            $this->command->info('✅ Usuario admin@sigram.com actualizado a tipo ADMIN exitosamente.');
            $this->command->info('   Email: ' . $usuario->email);
            $this->command->info('   Tipo: ' . $usuario->tipo_usuario);
        } else {
            $this->command->warn('⚠️  No se encontró el usuario admin@sigram.com');
            $this->command->info('Intentando buscar cualquier usuario con "admin" en el email...');

            $adminUsers = User::where('email', 'like', '%admin%')->get();

            if ($adminUsers->count() > 0) {
                $this->command->info('Usuarios encontrados con "admin" en el email:');
                foreach ($adminUsers as $user) {
                    $this->command->info('  - ' . $user->email . ' (ID: ' . $user->id . ', Tipo actual: ' . $user->tipo_usuario . ')');
                }
            } else {
                $this->command->error('❌ No se encontraron usuarios con "admin" en el email.');
            }
        }

        // Mostrar todos los usuarios tipo admin
        $this->command->info('');
        $this->command->info('Usuarios con tipo ADMIN en la base de datos:');
        $admins = User::where('tipo_usuario', 'admin')->get();

        if ($admins->count() > 0) {
            foreach ($admins as $admin) {
                $this->command->info('  - ' . $admin->email . ' (DNI: ' . ($admin->dni ?? 'N/A') . ')');
            }
        } else {
            $this->command->warn('⚠️  No hay usuarios con tipo ADMIN en la base de datos.');
        }
    }
}
