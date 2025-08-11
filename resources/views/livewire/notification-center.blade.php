<div class="relative" x-data="{ open: @entangle('showDropdown') }" x-on:click.away="open = false">
    
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 rounded-full">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19c-5 0-8-3-8-6 0-6 4-10 10-10s10 4 10 10c0 3-3 6-8 6h-1l-5 5v-5z"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-4 w-4 transform translate-x-1/2 -translate-y-1/2">
                <span class="absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75 animate-ping"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-xs font-bold text-white items-center justify-center">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            </span>
        @endif
    </button>

    
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
         x-cloak>
        
        
        <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $isAdmin ? 'Notificaciones Admin' : 'Mis Notificaciones' }}
                </h3>
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" class="text-sm text-teal-600 hover:text-teal-800">
                        Marcar todas como leídas
                    </button>
                @endif
            </div>
        </div>

        
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 {{ !$notification->read ? 'bg-blue-50' : '' }}" 
                     wire:key="notification-{{ $notification->id }}">
                    <div class="flex items-start space-x-3">
                        
                        <div class="flex-shrink-0">
                            @if($notification->type === 'points_awarded')
                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            @elseif($notification->type === 'low_stock')
                                <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            @elseif($notification->type === 'error')
                                <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @elseif($notification->type === 'order_update')
                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                @if(!$notification->read)
                                    <div class="h-2 w-2 bg-blue-600 rounded-full"></div>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $notification->message }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                                <div class="flex space-x-2">
                                    @if(!$notification->read)
                                        <button wire:click="markAsRead({{ $notification->id }})" 
                                                class="text-xs text-teal-600 hover:text-teal-800">
                                            Marcar leída
                                        </button>
                                    @endif
                                    <button wire:click="deleteNotification({{ $notification->id }})" 
                                            class="text-xs text-red-600 hover:text-red-800"
                                            onclick="return confirm('¿Eliminar esta notificación?')">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19c-5 0-8-3-8-6 0-6 4-10 10-10s10 4 10 10c0 3-3 6-8 6h-1l-5 5v-5z"></path>
                    </svg>
                    <p class="text-sm">No tienes notificaciones</p>
                </div>
            @endforelse
        </div>

        
        @if($notifications->hasPages())
            <div class="px-4 py-2 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
