@php
$user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="$user" />

            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Добро пожаловать {{ filament()->getUserName($user) }}
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Ваш кошелек - <a style="color: #0055FF;" href="https://tonviewer.com/{{ $user->address_wallet }}"
                        target="_blank">{{ $user->address_wallet }}</a>
                </p>
            </div>

            <div class="my-auto flex flex-col gap-3">
                @csrf



            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>