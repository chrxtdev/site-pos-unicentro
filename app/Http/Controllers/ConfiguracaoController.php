<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        // Puxa apenas usuários que tenham o booleano de admin legado ou tenham alguma role (do spatie)
        $admins = User::where('is_admin', true)->orWhereHas('roles')->orderBy('name')->get();
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();

        return view('admin.configuracoes.index', compact('admins', 'roles'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', Password::defaults()],
        ], [
            'name.required'     => 'O nome é obrigatório.',
            'email.required'    => 'O e-mail é obrigatório.',
            'email.unique'      => 'Este e-mail já está cadastrado.',
            'password.required' => 'A senha é obrigatória.',
            'password.min'      => 'A senha deve ter no mínimo 8 caracteres.',
            'role.required'     => 'O nível de acesso é obrigatório.',
            'role.exists'       => 'O nível de acesso selecionado é inválido.',
        ]);

        if (auth()->user()->hasRole('admin_comum')) {
            if (in_array($request->role, ['admin_master', 'financeiro'])) {
                abort(403, 'Você não tem permissão para criar administradores com este nível de acesso.');
            }
        }

        $admin = User::create([
            'name'     => strtoupper($request->name),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true, // Mantendo flag legado ativo
        ]);

        $admin->assignRole($request->role);

        return redirect()
            ->route('configuracoes.index')
            ->with('success', 'Administrador criado com sucesso!');
    }

    public function updateAdmin(Request $request, User $admin)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:8', Password::defaults()],
        ]);

        if (auth()->user()->hasRole('admin_comum')) {
            // Se o usuário alvo for nível superior, o admin comum não pode editá-lo.
            if ($admin->hasRole('admin_master') || $admin->hasRole('financeiro')) {
                abort(403, 'Você não tem permissão para editar este administrador.');
            }

            // O admin comum também não pode elevar o cargo para master ou financeiro
            if ($request->filled('role') && in_array($request->role, ['admin_master', 'financeiro'])) {
                abort(403, 'Você não tem permissão para atribuir este nível de acesso.');
            }
        }

        $admin->name = strtoupper($request->name);
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        if ($request->filled('role')) {
            $admin->syncRoles([$request->role]);
        }

        $admin->save();

        return redirect()
            ->route('configuracoes.index')
            ->with('success', 'Administrador atualizado com sucesso!');
    }

    public function destroyAdmin(User $admin)
    {
        if ($admin->id === auth()->id()) {
            return redirect()
                ->route('configuracoes.index')
                ->with('error', 'Você não pode remover a si mesmo.');
        }

        if (!$admin->is_admin) {
            return redirect()
                ->route('configuracoes.index')
                ->with('error', 'Este usuário não é um administrador.');
        }

        if (auth()->user()->hasRole('admin_comum')) {
            if ($admin->hasRole('admin_master') || $admin->hasRole('financeiro')) {
                abort(403, 'Você não tem permissão para remover este administrador.');
            }
        }

        $admin->delete();

        return redirect()
            ->route('configuracoes.index')
            ->with('success', 'Administrador removido com sucesso!');
    }

    public function storeAppConfig(Request $request)
    {
        // O formulário de configurações vai enviar um array field keys:
        // ex: <input name="configs[admin_whatsapp]">
        $configs = $request->input('configs', []);

        if (is_array($configs)) {
            foreach ($configs as $key => $value) {
                // Remove espaços do início e fim do valor antes de salvar
                if ($value !== null) {
                    $value = trim($value);
                }
                
                \App\Models\AppConfig::setValor($key, $value);
            }
        }

        return redirect()
            ->route('configuracoes.index')
            ->with('success', 'Configurações do sistema atualizadas com sucesso!');
    }
}