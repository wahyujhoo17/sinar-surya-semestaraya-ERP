@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    Notifikasi
                </h2>
                @if ($notifications->where('read_at', null)->count() > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                            Tandai semua sudah dibaca
                        </button>
                    </form>
                @endif
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($notifications as $notification)
                    <div class="p-6 {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $notification->data['title'] }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $notification->data['message'] }}
                                </p>
                                <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                    @if (!is_null($notification->read_at))
                                        <span class="mx-2">&bull;</span>
                                        <span>Dibaca {{ $notification->read_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="ml-4 flex items-center space-x-2">
                                @if (is_null($notification->read_at))
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                            Tandai dibaca
                                        </button>
                                    </form>
                                @endif

                                @if ($notification->data['link'])
                                    <a href="{{ $notification->data['link'] }}"
                                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                        Lihat detail
                                    </a>
                                @endif

                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada notifikasi</p>
                    </div>
                @endforelse
            </div>

            @if ($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
