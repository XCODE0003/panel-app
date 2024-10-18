<x-filament-panels::page>
    <form class="flex flex-col gap-2 max-w-md w-full " wire:submit="save">
        {{$this->form}}
        <x-filament::button type="submit" class="mt-2">
            Сохранить
        </x-filament::button>
    </form>
</x-filament-panels::page>