<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Crie sua Conta</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Preencha os dados abaixo para se cadastrar.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome
                Completo</label>
            <input id="name"
                class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors uppercase"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                placeholder="João da Silva" oninput="this.value = this.value.toUpperCase();" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail</label>
            <input id="email"
                class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                type="email" name="email" :value="old('email')" required autocomplete="username"
                placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Senha</label>
            <div class="relative">
                <input id="password"
                    class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors pr-10"
                    type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <button type="button" tabindex="-1"
                    onclick="const p=document.getElementById('password'); const i=this.querySelector('i'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation"
                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirmar Senha</label>
            <div class="relative">
                <input id="password_confirmation"
                    class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors pr-10"
                    type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="••••••••" />
                <button type="button" tabindex="-1"
                    onclick="const p=document.getElementById('password_confirmation'); const i=this.querySelector('i'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                Registrar Conta <i class="fa-solid fa-user-plus ml-2"></i>
            </button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-600 dark:text-slate-400">
                Já tem uma conta?
                <a href="{{ route('login') }}"
                    class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors">Faça
                    login</a>
            </p>
        </div>
    </form>
</x-guest-layout>
