{{-- resources/views/filament/widgets/create-attendance-button-widget.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::card>
        {{-- Widget content --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">Aksi Cepat</h2>
                <p class="text-sm text-gray-500">Buat entri kehadiran baru.</p>
            </div>
            <div>
                <x-filament::button
                    :href="route('filament.admin.resources.attendances.create')"
                    icon="heroicon-o-plus"
                    tag="a"
                >
                    New Attendance
                </x-filament::button>
            </div>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>
