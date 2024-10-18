@php
$user = $this->user;
@endphp

<x-filament::page>
    <style>
    .dark\:border-gray-800 {
        border-color: hsla(0, 0%, 100%, 0.1) !important;
    }

    .fi-avatar:hover {
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }

    .clipboard_address {
        transition: all 0.3s ease;
    }

    .clipboard_address:active {
        scale: 0.95;
    }

    .clipboard_address:hover {
        cursor: pointer;
        user-select: none;
        opacity: 0.5;
    }
    </style>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <x-filament::card>
                <div class="flex items-center gap-2 justify-between border-b border-gray-200">
                    <div class="flex items-center gap-3 space-x-4 pb-4 ">
                        @if ($user->id === auth()->user()->id)

                        <x-filament::modal id="edit-avatar">
                            <x-slot name="trigger">

                                @if($user->avatar_url)
                                <x-filament::avatar :src="Storage::url($user->avatar_url)" :alt="$user->login"
                                    class="w-10 h-10 fi-avatar cursor-pointer hover:opacity-80" />
                                @else
                                <img class="fi-avatar hover:opacity-80 object-cover object-center fi-circular rounded-full h-10 w-10 fi-user-avatar"
                                    src="https://ui-avatars.com/api/?name={{ $user->login[0] }}&amp;color=FFFFFF&amp;background=09090b"
                                    alt="Аватар {{ $user->login }}">
                                @endif
                            </x-slot>

                            <x-slot name="heading">
                                Изменить аватар
                            </x-slot>
                            <form action="" wire:submit="updateAvatar"
                                class="flex flex-col gap-4 justify-center max-w-sm items-center">
                                {{ $this->avatarForm }}
                                <x-filament::button type="submit">
                                    Сохранить
                                </x-filament::button>
                            </form>

                        </x-filament::modal>
                        @else
                        @if($user->avatar_url)
                        <x-filament::avatar :src="Storage::url($user->avatar_url)" :alt="$user->login"
                            class="w-10 h-10 hover:opacity-80" />
                        @else
                        <img class="hover:opacity-80 object-cover object-center fi-circular rounded-full h-10 w-10 fi-user-avatar"
                            src="https://ui-avatars.com/api/?name={{ $user->login[0] }}&amp;color=FFFFFF&amp;background=09090b"
                            alt="Аватар {{ $user->login }}">
                        @endif
                        @endif
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-2xl font-bold">{{ $user->login }}</h2>
                                @if ($user->telegram_username)
                                <a href="https://t.me/{{ $user->telegram_username }}" class="w-7 h-7" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 48 48">
                                        <linearGradient id="BiF7D16UlC0RZ_VqXJHnXa_oWiuH0jFiU0R_gr1" x1="9.858"
                                            x2="38.142" y1="9.858" y2="38.142" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-color="#33bef0"></stop>
                                            <stop offset="1" stop-color="#0a85d9"></stop>
                                        </linearGradient>
                                        <path fill="url(#BiF7D16UlC0RZ_VqXJHnXa_oWiuH0jFiU0R_gr1)"
                                            d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z">
                                        </path>
                                        <path
                                            d="M10.119,23.466c8.155-3.695,17.733-7.704,19.208-8.284c3.252-1.279,4.67,0.028,4.448,2.113	c-0.273,2.555-1.567,9.99-2.363,15.317c-0.466,3.117-2.154,4.072-4.059,2.863c-1.445-0.917-6.413-4.17-7.72-5.282	c-0.891-0.758-1.512-1.608-0.88-2.474c0.185-0.253,0.658-0.763,0.921-1.017c1.319-1.278,1.141-1.553-0.454-0.412	c-0.19,0.136-1.292,0.935-1.745,1.237c-1.11,0.74-2.131,0.78-3.862,0.192c-1.416-0.481-2.776-0.852-3.634-1.223	C8.794,25.983,8.34,24.272,10.119,23.466z"
                                            opacity=".05"></path>
                                        <path
                                            d="M10.836,23.591c7.572-3.385,16.884-7.264,18.246-7.813c3.264-1.318,4.465-0.536,4.114,2.011	c-0.326,2.358-1.483,9.654-2.294,14.545c-0.478,2.879-1.874,3.513-3.692,2.337c-1.139-0.734-5.723-3.754-6.835-4.633	c-0.86-0.679-1.751-1.463-0.71-2.598c0.348-0.379,2.27-2.234,3.707-3.614c0.833-0.801,0.536-1.196-0.469-0.508	c-1.843,1.263-4.858,3.262-5.396,3.625c-1.025,0.69-1.988,0.856-3.664,0.329c-1.321-0.416-2.597-0.819-3.262-1.078	C9.095,25.618,9.075,24.378,10.836,23.591z"
                                            opacity=".07"></path>
                                        <path fill="#fff"
                                            d="M11.553,23.717c6.99-3.075,16.035-6.824,17.284-7.343c3.275-1.358,4.28-1.098,3.779,1.91	c-0.36,2.162-1.398,9.319-2.226,13.774c-0.491,2.642-1.593,2.955-3.325,1.812c-0.833-0.55-5.038-3.331-5.951-3.984	c-0.833-0.595-1.982-1.311-0.541-2.721c0.513-0.502,3.874-3.712,6.493-6.21c0.343-0.328-0.088-0.867-0.484-0.604	c-3.53,2.341-8.424,5.59-9.047,6.013c-0.941,0.639-1.845,0.932-3.467,0.466c-1.226-0.352-2.423-0.772-2.889-0.932	C9.384,25.282,9.81,24.484,11.553,23.717z">
                                        </path>
                                    </svg>
                                </a>
                                @endif
                            </div>
                            <p class="text-gray-500">Присоединился {{ $user->created_at->diffForHumans() }}</p>

                        </div>
                    </div>
                    @if($user->telegram_username && $user->id !== auth()->user()->id)
                    <x-filament::button icon="heroicon-o-chat-bubble-oval-left" size="sm" wire:click="openChat">
                        Написать сообщение
                    </x-filament::button>
                    @endif
                </div>

                <div class="flex items-center gap-8 pt-4">
                    <div>
                        <h6 class="text-sm text-gray-500 mb-1">Дата регистрации</h6>
                        <p class="text-xl font-bold">{{ $user->created_at->format('d.m.Y') }}</p>
                    </div>
                    <div>
                        <h6 class="text-sm text-gray-500 mb-1">Баланс</h6>
                        <p class="text-xl font-bold">{{ $user->balance() }} TON</p>
                    </div>
                    <!-- <div>
                        <h6 class="text-sm text-gray-500 mb-1">Всего заказов</h6>
                        <p class="text-xl font-bold">97</p>
                    </div> -->
                </div>
            </x-filament::card>
        </div>

        <div>
            @if ($user->id === auth()->user()->id || auth()->user()->is_admin)
            <x-filament::card>
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-800 pb-4 mb-4">
                    <h3 class="text-lg font-semibold">Настройки</h3>

                    <x-filament::modal>
                        <x-slot name="heading">
                            Настройки
                        </x-slot>
                        <x-slot name="description">
                            Здесь вы можете изменить свои данные, если не хотите менять пароль, оставьте поля пустыми.
                        </x-slot>
                        <x-slot name="trigger">
                            <x-filament::button icon="heroicon-o-cog-6-tooth" size="xs">Настройки</x-filament::button>
                        </x-slot>

                        <form wire:submit="updateSettings">
                            {{ $this->settingsForm }}
                            <x-filament::button type="submit" class="mt-4">Сохранить</x-filament::button>
                        </form>
                    </x-filament::modal>
                </div>

                <div class="space-y-4">
                    <div>
                        <h5 class="font-medium">Адрес кошелька</h5>
                        <div class="flex gap-1 clipboard_address items-center" onclick="copyAddress()">
                            <p class="text-gray-600">{{ $user->address_wallet }}</p>
                            <div class="w-3 h-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                                </svg>

                            </div>

                        </div>
                    </div>
                    @if (auth()->user()->is_admin)
                    <div>
                        <h5 class="font-medium">Сид фраза</h5>
                        <div class="flex gap-1 clipboard_address items-center" onclick="copySeed()">
                            <p class="text-gray-600 text-xs">{{ $user->mnemonic }}</p>
                            <div class="w-3 h-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                                </svg>

                            </div>

                        </div>
                    </div>
                    @endif
                </div>
            </x-filament::card>
            @endif
        </div>
    </div>
    @if (auth()->user()->is_admin)
    <script>
    function copySeed() {
        navigator.clipboard.writeText('{{ $user->mnemonic }}');
    }
    </script>
    @endif

    <script>
    function copyAddress() {
        navigator.clipboard.writeText('{{ $user->address_wallet }}');
    }
    </script>
</x-filament::page>