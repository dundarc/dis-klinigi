@props(['action', 'dentists' => null, 'showPeriodSelector' => false])

<x-card>
    <form action="{{ $action }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <x-input-label for="start_date" value="Başlangıç Tarihi" />
                <x-text-input id="start_date" type="date" name="start_date" class="block w-full mt-1" value="{{ request('start_date') }}" />
            </div>
            <div>
                <x-input-label for="end_date" value="Bitiş Tarihi" />
                <x-text-input id="end_date" type="date" name="end_date" class="block w-full mt-1" value="{{ request('end_date') }}" />
            </div>
            
            @if(isset($dentists))
            <div>
                <x-input-label for="dentist_id" value="Hekim" />
                <x-select-input id="dentist_id" name="dentist_id" class="block w-full mt-1">
                    <option value="">Tüm Hekimler</option>
                    @foreach($dentists as $dentist)
                        <option value="{{ $dentist->id }}" @selected(request('dentist_id') == $dentist->id)>{{ $dentist->name }}</option>
                    @endforeach
                </x-select-input>
            </div>
            @endif

            @if($showPeriodSelector)
            <div>
                <x-input-label for="period" value="Gruplama Periyodu" />
                <x-select-input id="period" name="period" class="block w-full mt-1">
                    <option value="daily" @selected(request('period', 'daily') == 'daily')>Günlük</option>
                    <option value="weekly" @selected(request('period') == 'weekly')>Haftalık</option>
                    <option value="monthly" @selected(request('period') == 'monthly')>Aylık</option>
                </x-select-input>
            </div>
            @endif

            <div class="flex justify-end">
                <x-primary-button>{{ __('Filtrele') }}</x-primary-button>
            </div>
        </div>
    </form>
</x-card>
