<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Welcome back, {{ $this->getName() }}
        </x-slot>

        <x-slot name="description">
            You are signed in as {{ $this->getRoleLabel() }}. Everything on the public site is edited from here.
        </x-slot>

        <div class="flex flex-wrap gap-3">
            @foreach ($this->getLinks() as $link)
                <x-filament::button
                    tag="a"
                    :href="$link['url']"
                    :icon="$link['icon']"
                    color="gray"
                    :target="($link['new_tab'] ?? false) ? '_blank' : null"
                >
                    {{ $link['label'] }}
                </x-filament::button>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
