<x-authentication-layout>
    <div class="bg-white p-8 shadow-xl rounded-3xl">
    <h1 class="text-3xl font-bold text-[#3D3D3D] mb-2">Heladería <span class="text-[#F28482]">Solé</span></h1>
     <p class="text-[#3D3D3D]/60 mb-6">Inicia sesión para entrar a tu galería 🍦</p>
    {{-- <h1 class="text-3xl text-slate-800 dark:text-slate-100 font-bold mb-6 ">{{ __('Sole') }}</h1> --}}
    
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif
    <!-- Form -->
    
    <form method= "POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-4 ">
            <div>
                {{-- <x-label for="email" value="{{ __('Correo') }}" /> --}}
                <div style="position: relative; display ; inline-block;width:100%;">
                    <x-input style="background-color: white;border:none; box-shadow:0 2px 4px rgba(0,0,0,0.1)" placeholder="Correo" id="email" type="email" name="email" :value="old('email')" required autofocus />
                </div>
                
            </div>
            <div>
                {{-- <x-label for="password" value="{{ __('Contraseña') }}" /> --}}
                <x-input style="background-color: white;border:none; box-shadow:0 2px 4px rgba(0,0,0,0.1)" id="password" placeholder="Contraseña" type="password" name="password" required autocomplete="current-password" />
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            {{-- @if (Route::has('password.request'))
                <div class="mr-1">
                    <a class="text-sm underline hover:no-underline" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>
            @endif --}}
            <x-button style="background: linear-gradient(135deg, #f37a91 0%, #f589a7 100%); color: #ffffff; border-radius:8px" class="ml-3">
                {{ __('Iniciar sesión') }}
            </x-button>
            </div>
    </form>
    </div>
    <x-validation-errors class="mt-4" />
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-slate-200 dark:border-slate-700">

        <!-- Warning -->
        <div class="mt-5">
            <div class="bg-amber-100 dark:bg-amber-400/30 text-amber-600 dark:text-amber-400 px-3 py-2 rounded">
                <svg class="inline w-3 h-3 shrink-0 fill-current" viewBox="0 0 12 12">
                    <path
                        d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                </svg>
                <span class="text-sm">
                    Heladeria Solé te da la bienvenida, por favor inicia sesión para continuar.
                </span>
            </div>
        </div>
    </div>
</x-authentication-layout>
