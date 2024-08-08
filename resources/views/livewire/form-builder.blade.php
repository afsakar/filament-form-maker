<div
    style="background-color: {{ data_get($options, 'background_color') ?? 'transparent'}} !important;"
    class="w-full"
>
    <div class="mx-auto form-field" wire:ignore.self>
        <form wire:submit="save">

            {{ $this->form }}

            <div class="flex items-center justify-end my-10">
                <x-filament::button type="submit" class="md:w-[25%] w-full" style="background-color: rgba(var(--primary-600),var(--tw-text-opacity));">
                    Submit
                </x-filament::button>
            </div>
        </form>

        <x-filament-actions::modals />
    </div>
</div>
