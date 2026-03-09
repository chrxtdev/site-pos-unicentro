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

        if (!$user->is_admin) {
            return redirect()->route('aluno.portal');
        }

        return redirect()->route('admin.dashboard');
    }
}
