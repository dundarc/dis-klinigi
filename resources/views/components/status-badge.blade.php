@props(['status', 'type' => 'invoice'])

@php
    $statusValue = $status->value ?? $status;
    $statusConfig = [
        'invoice' => [
            'paid' => ['label' => 'Ödenmiş', 'class' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200'],
            'unpaid' => ['label' => 'Ödenmemiş', 'class' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200'],
            'partial' => ['label' => 'Kısmi Ödeme', 'class' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200'],
            'taksitlendirildi' => ['label' => 'Taksitli', 'class' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200'],
            'vadeli' => ['label' => 'Vadeli', 'class' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200'],
            'vadesi_gecmis' => ['label' => 'Vadesi Geçmiş', 'class' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200'],
            'draft' => ['label' => 'Taslak', 'class' => 'bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200'],
            'issued' => ['label' => 'Düzenlendi', 'class' => 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-800 dark:text-cyan-200'],
            'cancelled' => ['label' => 'İptal Edildi', 'class' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200'],
        ]
    ];

    $config = $statusConfig[$type][$statusValue] ?? [
        'label' => ucfirst(str_replace(['_', '-'], ' ', $statusValue)),
        'class' => 'bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200'
    ];
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $config['class'] }}">
    {{ $config['label'] }}
</span>