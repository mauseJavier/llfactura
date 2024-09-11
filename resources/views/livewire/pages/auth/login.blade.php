<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('panel'));
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status style="margin-top: 50px;" :status="session('status')" />

    <div class="container" >
        <article style="margin-top: 50px;" >

            <form wire:submit="login" style=" width:  80%; margin: 0 auto ;">
                <!-- Email Address -->
                <div >
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>
        
                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
        
                    <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
        
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>
        
                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                @if (Route::has('password.request'))
                <article>
                    <div class="grid">
                        <div class="col">
                            <a  href="{{ route('password.request') }}" wire:navigate>
                                {{ __('Perdio la Contraseña?') }}
                            </a>
                        </div>
                        <div class="col">
                            <a  href="{{ route('register') }}" wire:navigate>
                                {{ __('Registrarse') }}
                            </a>

                        </div>
                    </div>
                </article>
                @endif
    
        
                    <x-primary-button class="ms-3">
                        {{ __('Entrar') }}
                    </x-primary-button>
                </div>
            </form>

        </article>

    </div>
   
</div>
