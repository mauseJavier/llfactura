<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;


new #[Layout('layouts.guest')] class extends Component
{

  
    public string $name = '';

    public string $email = '';

    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {

        $validated = $this->validate([
            'name' => ['string','required','max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no debe exceder los 255 caracteres.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.string' => 'El campo correo electrónico debe ser una cadena de texto.',
            'email.lowercase' => 'El campo correo electrónico debe estar en minúsculas.',
            'email.email' => 'El campo correo electrónico debe ser una dirección de correo válida.',
            'email.max' => 'El campo correo electrónico no debe exceder los 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.string' => 'El campo contraseña debe ser una cadena de texto.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);


        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('panel', absolute: false), navigate: true);
    }
}; ?>

<div>

    <div class="container">

        <form wire:submit="register">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
    
            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Correo')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Contraseña')" />
    
                <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
    
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
    
            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
    
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
    
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
    
            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}" wire:navigate>
                    {{ __('YA esta Registrado?') }}
                </a>
    
                <x-primary-button class="ms-4">
                    {{ __('Registrar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
