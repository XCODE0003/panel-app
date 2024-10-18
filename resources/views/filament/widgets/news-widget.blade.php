@php
$news = App\Models\News::all();
@endphp
<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <style>
        .fi-account-widget-element {
            transition: all 0.3s ease-in-out;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .fi-account-widget-element:hover {
            border: 1px solid #000;
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        </style>
        <div class="grid grid-cols-3 gap-4">

            @foreach ($news as $item)
            <x-filament::modal>
                <x-slot name="heading">
                    {{ $item->title }}
                </x-slot>


                <x-slot name="trigger">
                    <div class=" fi-account-widget-element p-4 rounded-lg shadow-md">
                        @if ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                            class="mt-2 rounded-md">
                        @endif

                        <h2 class="text-lg font-semibold">{{ $item->title }}</h2>
                        <p class="text-sm text-gray-600">{{ $item->content }}</p>

                    </div>
                </x-slot>

                {{ $item->content }}
            </x-filament::modal>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>