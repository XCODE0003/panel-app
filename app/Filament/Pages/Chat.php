<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use App\Models\Message;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use App\Models\Chat as ChatModel;
use Illuminate\Contracts\View\View;

class Chat extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string $view = 'filament.pages.chat';
    protected static ?string $navigationLabel = 'Мессенджер';

    public ?int $chatId = null;
    public ChatModel $currentChat;

    public ?array $messageData = [];
    public ?array $filterActionsData = [];
    public ?string $activeFilter = null;

    public function mount($chatId = null): void
    {
        if ($chatId) {
            $this->chatId = $chatId;
            $this->currentChat = ChatModel::find($chatId);
        } else {
            $this->chatId = auth()->user()->getLatestChatId();
            $this->currentChat = ChatModel::find($this->chatId);
        }

        $this->fillForms();
    }

    protected function getForms(): array
    {
        return [
            'messageForm' => $this->makeForm()
                ->schema([
                    TextInput::make('message')
                        ->label('')
                        ->placeholder('Введите сообщение...')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true),
                ])
                ->statePath('messageData'),

            'filterActionsForm' => $this->makeForm()
                ->schema([
                    Select::make('status')
                        ->options([
                            'approved' => "#approved",
                            'requested' => "#requested",
                            'connection' => "#connection",
                            'fee' => "#fee",
                        ])
                        ->label('Тип уведомлений')
                        ->live()
                        ->afterStateUpdated(function ($state) {
                            $this->filterActions($state);
                        })
                ])
                ->statePath('filterActionsData'),
        ];
    }

    public function fillForms(): void
    {
        $this->messageForm->fill();
        $this->filterActionsForm->fill();
    }

    public function filterActions($status = null): void
    {
        $this->activeFilter = $status;
        $this->dispatch('refreshMessages');
    }

    public function setChat(int $chatId): void
    {
        $this->chatId = $chatId;
        $this->currentChat = ChatModel::find($chatId);
        $this->fillForms();
        $this->activeFilter = null;
        $this->dispatch('chatChanged');
    }

    public function sendMessage(): void
    {
        $data = $this->messageForm->getState();

        if (empty($data['message'])) {
            Notification::make()
                ->title('Ошибка')
                ->body('Сообщение не может быть пустым')
                ->danger()
                ->send();
            return;
        }

        try {
            $chat = ChatModel::find($this->chatId);
            Message::create([
                'user_id' => auth()->id(),
                'chat_id' => $this->chatId,
                'content' => $data['message'],
            ]);

            $chat->touch();

            $this->messageForm->fill();
            $this->dispatch('messageAdded');

            Notification::make()
                ->title('Сообщение отправлено')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Ошибка')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    #[Computed]
    public function messages(): array
    {
        $query = Message::with('user')
            ->where('chat_id', $this->chatId);

        if ($this->activeFilter) {
            $query->where('content', 'like', '%' . $this->activeFilter . '%');
        }

        return $query->latest()
            ->get()
            ->reverse()
            ->values()
            ->toArray();
    }

    public function getListeners()
    {
        return [
            'echo:chat.' . $this->chatId . ',MessageSent' => '$refresh',
            'refreshMessages' => '$refresh',
        ];
    }

    public static function getSlug(): string
    {
        return 'chat/{chatId?}';
    }
}