<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Formulário: Atualizar dados do perfil --}}
            <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h3 class="text-lg font-medium text-gray-900">Informações Pessoais</h3>
                </header>
                <div class="mt-6 max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            {{-- Formulário: Atualizar senha --}}
            <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h3 class="text-lg font-medium text-gray-900">Alterar Senha</h3>
                </header>
                <div class="mt-6 max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            {{-- Formulário: Deletar conta --}}
            <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h3 class="text-lg font-medium text-red-600">Excluir Conta</h3>
                </header>
                <div class="mt-6 max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
