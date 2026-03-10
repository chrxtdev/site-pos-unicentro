<section>
    <header>
        <h2 class="text-lg font-medium text-slate-900 dark:text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="relative">
                <x-text-input id="update_password_current_password" name="current_password" type="password"
                    class="mt-1 block w-full pr-10" autocomplete="current-password" />
                <button type="button" tabindex="-1"
                    onclick="const p=document.getElementById('update_password_current_password'); const i=this.querySelector('i'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative">
                <x-text-input id="update_password_password" name="password" type="password"
                    class="mt-1 block w-full pr-10" autocomplete="new-password" />
                <button type="button" tabindex="-1"
                    onclick="const p=document.getElementById('update_password_password'); const i=this.querySelector('i'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="mt-1 block w-full pr-10" autocomplete="new-password" />
                <button type="button" tabindex="-1"
                    onclick="const p=document.getElementById('update_password_password_confirmation'); const i=this.querySelector('i'); if(p.type==='password'){p.type='text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash');}else{p.type='password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye');}"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-600 dark:text-slate-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
