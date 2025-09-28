<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Takvim Dışa Aktarım - {{ $monthLabel }}</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; margin: 20px; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .appointment { margin-bottom: 5px; padding: 5px; border-radius: 3px; font-size: 11px; }
        .scheduled { background-color: #dbeafe; color: #1e40af; }
        .confirmed { background-color: #d1fae5; color: #065f46; }
        .checked_in { background-color: #fef3c7; color: #92400e; }
        .in_service { background-color: #e0e7ff; color: #3730a3; }
        .completed { background-color: #f1f5f9; color: #334155; }
        .cancelled { background-color: #fee2e2; color: #dc2626; }
        .no_show { background-color: #fed7aa; color: #9a3412; }
    </style>
</head>
<body>
    <h1>{{ __('Takvim') }} - {{ $monthLabel }}</h1>

    @if($view === 'month')
        <table>
            <thead>
                <tr>
                    @foreach($weekDays as $dayName)
                        <th>{{ $dayName }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @for($i = 0; $i < 6; $i++)
                    <tr>
                        @for($j = 0; $j < 7; $j++)
                            @php
                                $dayIndex = $i * 7 + $j;
                                $day = $days[$dayIndex] ?? null;
                            @endphp
                            <td @if($day && !$day['isCurrentMonth']) style="background-color: #f9f9f9;" @endif>
                                @if($day)
                                    <strong>{{ $day['date']->format('j') }}</strong>
                                    @foreach($day['appointments'] as $appointment)
                                        @php
                                            $statusClass = $appointment->status->value;
                                        @endphp
                                        <div class="appointment {{ $statusClass }}">
                                            {{ $appointment->start_at->format('H:i') }} - {{ $appointment->dentist->name }}<br>
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    @elseif($view === 'week')
        <table>
            <thead>
                <tr>
                    <th>{{ __('Time') }}</th>
                    @foreach($days as $day)
                        <th>{{ $day['date']->format('D j M') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $hours = range(8, 18); @endphp
                @foreach($hours as $hour)
                    <tr>
                        <td>{{ sprintf('%02d:00', $hour) }}</td>
                        @foreach($days as $day)
                            <td>
                                @php
                                    $hourData = $day['hours']->firstWhere('hour', $hour);
                                @endphp
                                @if($hourData && $hourData['appointments']->count() > 0)
                                    @foreach($hourData['appointments'] as $appointment)
                                        @php $statusClass = $appointment->status->value; @endphp
                                        <div class="appointment {{ $statusClass }}">
                                            {{ $appointment->start_at->format('H:i') }}<br>
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}<br>
                                            {{ $appointment->dentist->name }}
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($view === 'day')
        <table>
            <thead>
                <tr>
                    <th>{{ __('Time') }}</th>
                    <th>{{ $days->first()['date']->format('D j M Y') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $hours = range(8, 18); @endphp
                @foreach($hours as $hour)
                    <tr>
                        <td>{{ sprintf('%02d:00', $hour) }}</td>
                        <td>
                            @php
                                $day = $days->first();
                                $hourData = $day['hours']->firstWhere('hour', $hour);
                            @endphp
                            @if($hourData && $hourData['appointments']->count() > 0)
                                @foreach($hourData['appointments'] as $appointment)
                                    @php $statusClass = $appointment->status->value; @endphp
                                    <div class="appointment {{ $statusClass }}">
                                        {{ $appointment->start_at->format('H:i') }}<br>
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}<br>
                                        {{ $appointment->dentist->name }}
                                    </div>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>