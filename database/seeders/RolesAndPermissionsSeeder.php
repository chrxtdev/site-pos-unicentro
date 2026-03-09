<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpar cache do Spatie antes de recriar as roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões básicas caso no futuro queira descer ao nível de permissão (agora vamos focar em roles)
        $permissions = [
            'view_financeiro',
            'manage_financeiro',
            'view_notas',
            'manage_notas',
            'view_cursos',
            'manage_cursos',
            'view_alunos',
            'manage_alunos',
            'view_atividades',
            'manage_atividades',
            'manage_configuracoes',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // --- Criar Roles e Atribuir Permissões ---

        // 1. Admin Master (Acesso Total a Tudo)
        $roleMaster = Role::findOrCreate('admin_master', 'web');
        $roleMaster->givePermissionTo(Permission::all());

        // 2. Financeiro (Acesso Total ao Sistema, focado na gestão financeira, conforme alinhado)
        $roleFinanceiro = Role::findOrCreate('financeiro', 'web');
        $roleFinanceiro->givePermissionTo(Permission::all());

        // 3. Admin Comum (Acesso Gestão, exceto Financeiro e Configurações Globais sensíveis)
        $roleComum = Role::findOrCreate('admin_comum', 'web');
        $roleComum->givePermissionTo([
            'view_notas', 'manage_notas', 'view_cursos', 'manage_cursos',
            'view_alunos', 'manage_alunos', 'view_atividades', 'manage_atividades'
        ]);

        // 4. Professor (Acesso restrito só Atividades e Notas via Painel Prof)
        $roleProfessor = Role::findOrCreate('professor', 'web');
        $roleProfessor->givePermissionTo([
            'view_notas', 'manage_notas', 'view_atividades', 'manage_atividades'
        ]);

        // --- Atribuir os admins atuais para Admin Master (para não perderem acesso) ---
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            if (!$admin->hasRole('admin_master')) {
                $admin->assignRole('admin_master');
            }
        }
    }
}
