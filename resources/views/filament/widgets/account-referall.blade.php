@php
    $user = filament()->auth()->user();
        $referrals = $user->where('referral_user', $user->id)->get();
@endphp


<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="$user"/>

            <div class="flex-1">
                <h2
                    class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white"
                >
                    Реферальная программа
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    У вас {{ count($referrals->toArray()) }} рефералов
                </p>
            </div>

            <div
                class="my-auto flex flex-col gap-3"
            >
                @csrf

                <x-filament::input.wrapper suffix-icon="heroicon-m-globe-alt">
                    <x-filament::input
                        type="url"
                        disabled
                        wire:model="copy"
                        value="{{ route('index', ['ref' => $user->id]) }}"
                    />
                </x-filament::input.wrapper>

            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>


