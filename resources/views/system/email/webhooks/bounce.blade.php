<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">E-posta Bounce Kayıtları</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Geri dönen e-posta kayıtları</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('system.email.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    E-posta Ayarlarına Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="bounce_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Bounce Türü</label>
                        <select id="bounce_type" name="bounce_type" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200">
                            <option value="">Tümü</option>
                            <option value="hard" {{ request('bounce_type') == 'hard' ? 'selected' : '' }}>Hard Bounce</option>
                            <option value="soft" {{ request('bounce_type') == 'soft' ? 'selected' : '' }}>Soft Bounce</option>
                            <option value="complaint" {{ request('bounce_type') == 'complaint' ? 'selected' : '' }}>Şikayet</option>
                            <option value="other" {{ request('bounce_type') == 'other' ? 'selected' : '' }}>Diğer</option>
                        </select>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">E-posta Adresi</label>
                        <input type="email" id="email" name="email" value="{{ request('email') }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                               placeholder="user@example.com">
                    </div>

                    <div>
                        <label for="provider" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sağlayıcı</label>
                        <input type="text" id="provider" name="provider" value="{{ request('provider') }}"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-slate-200"
                               placeholder="Mailgun, SES, vb.">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Filtrele
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bounce Records Table -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Bounce Kayıtları</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">E-posta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Tür</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Sağlayıcı</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Tarih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">İlgili Mail</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($bounces as $bounce)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">
                                    {{ $bounce->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($bounce->bounce_type === 'hard') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @elseif($bounce->bounce_type === 'soft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($bounce->bounce_type === 'complaint') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                        @switch($bounce->bounce_type)
                                            @case('hard') Hard Bounce @break
                                            @case('soft') Soft Bounce @break
                                            @case('complaint') Şikayet @break
                                            @default Diğer
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                    {{ $bounce->provider ?: '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                    {{ $bounce->occurred_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                    @if($bounce->emailLog)
                                        <a href="{{ route('system.email.logs.show', $bounce->emailLog) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ Str::limit($bounce->emailLog->subject, 30) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="showBounceDetails({{ $bounce->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Detaylar
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                                    Bounce kaydı bulunamadı.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bounces->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $bounces->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bounce Details Modal -->
    <div id="bounceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-slate-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Bounce Detayları</h3>
                    <button onclick="closeBounceModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="bounceContent" class="text-sm text-slate-600 dark:text-slate-400">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBounceDetails(bounceId) {
            fetch(`/system/email/webhooks/bounce/${bounceId}`)
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('bounceContent');
                    content.innerHTML = `
                        <div class="space-y-4">
                            <div><strong>E-posta:</strong> ${data.email}</div>
                            <div><strong>Tür:</strong> ${data.bounce_type}</div>
                            <div><strong>Sağlayıcı:</strong> ${data.provider || '-'}</div>
                            <div><strong>Tarih:</strong> ${new Date(data.occurred_at).toLocaleString('tr-TR')}</div>
                            <div><strong>Ham Veri:</strong></div>
                            <pre class="bg-slate-100 dark:bg-slate-700 p-3 rounded text-xs overflow-x-auto">${JSON.stringify(data.raw_payload, null, 2)}</pre>
                        </div>
                    `;
                    document.getElementById('bounceModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading bounce details:', error);
                });
        }

        function closeBounceModal() {
            document.getElementById('bounceModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('bounceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBounceModal();
            }
        });
    </script>
</x-app-layout>