<div>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Сохранить аватар
        </x-filament::button>
    </form>
</div>