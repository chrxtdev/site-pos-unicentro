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
            <label for="login" class="block text-sm font-bold text-slate-300 mb-1">E-mail, CPF ou Matrícula</label>
            <input id="login"
                class="block w-full px-4 py-3 rounded-xl border-white/10 bg-white/5 text-white placeholder-slate-500 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all outline-none"
                type="text" name="login" :value="old('login')" required autofocus autocomplete="username"
                placeholder="Insira sua identificação..." />
            <x-input-error :messages="$errors->get('login')" class="mt-2 text-red-400 font-medium" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-bold text-slate-300">Senha</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-emerald-400 hover:text-emerald-300 font-bold transition-colors"
                        href="{{ route('password.request') }}">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <input id="password"
                    class="block w-full px-4 py-3 rounded-xl border-white/10 bg-white/5 text-white placeholder-slate-500 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all pr-12 outline-none"
                    type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <button type="button" tabindex="-1"
                    onclick="const p=document.getElementById('password'); const i=this.querySelector('i'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 group-hover:text-emerald-500 focus:outline-none transition-colors">
                    <i class="fa-regular fa-eye text-lg"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 font-medium" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox"
                    class="rounded-lg border-white/10 bg-white/5 text-emerald-600 shadow-sm focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer w-5 h-5 transition-all"
                    name="remember">
                <span class="ms-3 text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Lembrar de mim</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_10px_20px_-5px_rgba(16,183,116,0.4)] text-base font-black text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:-translate-y-1 active:scale-95 leading-none">
                ACESSAR PORTAL <i class="fa-solid fa-chevron-right ml-3 text-xs"></i>
            </button>
        </div>


    </form>
</x-guest-layout>
