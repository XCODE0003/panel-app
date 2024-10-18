@php
use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use App\Models\Setting;
$messages = Message::get();

$settings = Setting::first();
$chats = auth()->user()->chats;
function isGroup($chatId, $chats) {
foreach ($chats as $chat) {
if ($chat->id == $chatId) {
return $chat->is_group;
}
}
return false;
}
foreach ($chats as $chat) {

$chat->lastMessage = $chat->messages()->latest()->first();
if($chat->user_id_sender == auth()->user()->id) {
$chat->interlocutor = User::find($chat->user_id_recipient);
} else {
$chat->interlocutor = User::find($chat->user_id_sender);
}
}





@endphp

<x-filament-panels::page>
    <style>
    .fi-avatar {
        transition: opacity 0.3s ease;
    }

    .a-link:hover .fi-avatar {
        opacity: 0.6;

    }
    </style>
    <div class="flex gap-2">
        <div style="max-width: 250px; "
            class="fi-section p-4 w-full rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex   w-full flex-col gap-2">

                @foreach ($chats as $chat)
                <div wire:click="setChat({{ $chat->id }})"
                    class="cursor-pointer chat-item {{ $chat->id === $chatId ? 'active' : '' }} flex items-center gap-2">
                    @if($chat->is_group)
                    <img src="{{ asset('storage/' . $chat->avatar) }}" alt="avatar" class="w-10 h-10 rounded-full">
                    <div class="flex flex-col ">
                        <p class="text-sm font-semibold">{{ $chat->name }}</p>
                        <p class="text-xs text-gray-500">{{ $chat?->lastMessage?->content }}</p>
                    </div>
                    @else
                    @if ($chat?->interlocutor?->avatar_url )
                    <img src="{{ asset('storage/' . $chat->interlocutor->avatar_url) }}" alt="avatar"
                        class="w-10 h-10 rounded-full">
                    @else
                    <img class=" hover:opacity-80 object-cover object-center fi-circular rounded-full h-10 w-10 fi-user-avatar"
                        src="https://ui-avatars.com/api/?name={{ $chat?->interlocutor?->login[0] }}&amp;color=FFFFFF&amp;background=09090b"
                        alt="Аватар {{ $chat?->interlocutor?->login }}">
                    @endif
                    <div class="flex flex-col ">
                        <p class="text-sm font-semibold">{{ $chat?->interlocutor?->login }}</p>
                        <p class="text-xs text-gray-500">{{ $chat?->lastMessage?->content }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
 
        <div style="height: calc(80vh)" class="flex flex-1 flex-col max-w-7xl mx-auto ">
            @if ($settings->group_id_botProfit == $chatId)
            <div class="w-full rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <form wire:submit.prevent="filterActions" class="p-4 flex gap-2 items-center">
                    {{$this->filterActionsForm}}
                 
                </form>
            </div>
            @endif 
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages" wire:poll.2s="$refresh">
                
                @foreach ($this->messages as $message)
                <div class="flex {{ $message['user_id'] === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="flex gap-2 items-center">
                        @if($message['user_id'] !== auth()->id() && isGroup($chatId, $chats))
                        @if($message['user']['avatar_url'])
                        <a class="a-link" href="/user/profile/{{ $message['user']['id'] }}">
                            <img src="{{ asset('storage/' . $message['user']['avatar_url']) }}" alt="avatar"
                                class="w-10 h-10 rounded-full fi-avatar">
                        </a>
                        @else
                        <a class="a-link" href="/user/profile/{{ $message['user']['id'] }}">
                            <img class="fi-avatar  object-cover object-center fi-circular rounded-full h-10 w-10 fi-user-avatar"
                                src="https://ui-avatars.com/api/?name={{ $message['user']['login'][0] }}&amp;color=FFFFFF&amp;background=09090b"
                                alt="Аватар {{ $message['user']['login'] }}">
                        </a>
                        @endif
                        @endif
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-3 max-w-[70%]">
                            @if(isGroup($chatId, $chats) && $message['user_id'] !== auth()->id())
                            <p class="text-xs text-gray-500 font-semibold">{{ $message['user']['login'] }}</p>
                            @endif
                            <p>{!! $message['content'] !!}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if($this->currentChat && $this->currentChat->allow_messages || auth()->user()->is_admin)
            <div class="p-4 ">
                <form wire:submit.prevent="sendMessage" class="flex form-message gap-2 items-center">
                    {{ $this->messageForm }}
                    <x-filament::button type="submit" class="mt-2">
                        Отправить
                    </x-filament::button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('livewire:initialized', () => {
        const scrollToBottom = () => {
            const chatMessages = document.getElementById('chat-messages');
            setTimeout(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 100);
        };
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
        @this.on('messageAdded', scrollToBottom);

        @this.on('chatChanged', () => {
            Livewire.dispatch('$refresh');
            scrollToBottom();
        });

        // Добавим обработчик для события обновления
        Livewire.on('$refresh', scrollToBottom);
    });
    </script>
    <style>
    .fi-header {
        display: none !important;
    }

    .chat-item {
        padding: 10px;
        transition: background-color 0.3s ease-in-out;
        border-radius: 10px;
    }

    .chat-item.active,
    .chat-item:hover {
        background-color: #e0e0e0;
    }

    .dark .chat-item.active,
    .dark .chat-item:hover {
        background-color: #2c2c2c;
    }

    .fi-main {
        max-width: 100%;
        margin: unset;
    }

    .form-message .fi-fo-component-ctn {
        flex: 1;
    }

    .fi-sidebar {
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        bottom: 0 !important;
        width: var(--sidebar-width) !important;
        z-index: 50 !important;
        transition: transform 0.3s ease-in-out !important;
    }

    .fi-fo-field-wrp-label {
        display: none;
    }

    .fi-sidebar:not(.fi-sidebar-open) {
        transform: translateX(-100%) !important;
    }

    .fi-sidebar.fi-sidebar-open {
        transform: translateX(0) !important;
    }

    .fi-topbar-open-sidebar-btn {
        display: flex !important;
    }

    .fi-sidebar-close-overlay.lg\:hidden {
        display: unset;
    }

    .fi-sidebar {
        background-color: white !important;
    }

    .dark .fi-sidebar {
        background-color: rgba(var(--gray-900), var(--tw-bg-opacity)) !important;
    }


    .fi-main {
        transition: padding-left 0.3s ease-in-out !important;
    }

    .fi-sidebar-open~.fi-main {
        padding-left: var(--sidebar-width) !important;
    }

    /* Отключаем все медиа-запросы для боковой панели */
    @media (min-width: 1024px) {
        .fi-sidebar {
            width: var(--sidebar-width) !important;
        }

        .fi-sidebar:not(.fi-sidebar-open) {
            transform: translateX(-100%) !important;
        }

        .fi-sidebar.fi-sidebar-open {
            transform: translateX(0) !important;
        }
    }
    </style>
</x-filament-panels::page>