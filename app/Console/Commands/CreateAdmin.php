<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature_description = 'Cria um novo usuário Administrador Master manualmente.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('--- Criador de Administrador Master ---');

        $name = $this->ask('Nome do Administrador');
        $email = $this->ask('E-mail (Login)');
        $password = $this->secret('Senha');

        if ($this->confirm("Deseja criar o usuário {$name} ({$email}) como Admin Master?", true)) {
            
            // Verifica se a role existe
            $role = Role::where('name', 'admin_master')->first();
            if (!$role) {
                $this->error('A Role "admin_master" não foi encontrada. Certifique-se de ter rodado o RolesAndPermissionsSeeder.');
                return 1;
            }

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
            ]);

            $user->assignRole($role);

            $this->info("Usuário {$email} criado com sucesso e vinculado à role admin_master!");
        } else {
            $this->info('Operação cancelada.');
        }

        return 0;
    }
}
