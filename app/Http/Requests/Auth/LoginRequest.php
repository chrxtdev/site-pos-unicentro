<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $password = $this->input('password');
        $credentials = [];

        // Verifica o que o usuário preencheu (email, cpf ou matrícula)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $login, 'password' => $password];
        } else {
            // Pode ser CPF ou Matrícula
            $cleanLogin = preg_replace('/[^a-zA-Z0-9]/', '', $login);
            
            // Vamos testar se existe essa matrícula
            $aluno = \App\Models\Signin::where('matricula', $login)->first();
            
            if (!$aluno) {
                // Tenta achar pelo CPF criptografado, temos que iterar ou usar query mais simples se possível
                // O jeito mais seguro sem leak é buscar alunos ou buscar se tem login idêntico
                // Como não sabemos qual aluno tem o cpf exato no banco criptografado, procuramos
                // o login original não-ofuscado na base:
                $alunos = \App\Models\Signin::all();
                foreach($alunos as $a) {
                    $cpfDoBanco = preg_replace('/[^0-9]/', '', $a->cpf);
                    if ($cpfDoBanco === $cleanLogin || $a->cpf === $login) {
                        $aluno = $a;
                        break;
                    }
                }
            }

            if ($aluno) {
                $credentials = ['email' => $aluno->email, 'password' => $password];
            } else {
                // Tenta cair no Auth::attempt original sabendo que vai falhar
                $credentials = ['email' => $login, 'password' => $password];
            }
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
