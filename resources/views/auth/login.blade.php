<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Bem-vindo de volta!</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Acesse sua conta para continuar.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Login (Email, CPF ou RA) -->
        <div>
            <label for="login" class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail, CPF ou Matrícula</label>
            <input id="login"
                class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                type="text" name="login" :value="old('login')" required autofocus autocomplete="username"
                placeholder="Insira sua identificação..." />
            <x-input-error :messages="$errors->get('login')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Senha</label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 font-medium transition-colors"
                        href="{{ route('password.request') }}">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>
            <input id="password"
                class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:ring-offset-0 bg-white dark:bg-slate-900 cursor-pointer"
                    name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-400">Lembrar de mim</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                Entrar no Sistema <i class="fa-solid fa-right-to-bracket ml-2"></i>
            </button>
        </div>


    </form>
</x-guest-layout>
