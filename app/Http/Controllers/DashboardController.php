<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Signin;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->hasRole(['admin_master', 'financeiro', 'admin_comum', 'professor']) || $user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('aluno.portal');
    }
}
