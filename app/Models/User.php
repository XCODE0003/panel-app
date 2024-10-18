<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Chat;
use App\Models\Message;
use App\Services\Ton\WalletGenerator;

class User extends Authenticatable implements FilamentUser, HasName, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable;
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }
    public function balance(): float
    {
        return (new WalletGenerator)->getUserBalance($this->address_wallet);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'password',
        'telegram_username',
        'mnemonic',
        'is_admin',
        'address_wallet',
        'remember_token',
        'avatar_url',
        'limit_domain',
        'is_banned'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getFilamentName(): string
    {
        return $this->login;
    }
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Получить все чаты пользователя.
     */
    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id_sender')
            ->orWhere('user_id_recipient', $this->id)->orWhere('is_group', true)->orderBy('updated_at', 'desc');
    }
    public function getLatestChatId()
    {
        return $this->chats()->orderBy('updated_at', 'desc')->first()?->id;
    }
    /**
     * Получить все сообщения пользователя.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Получить URL аватара пользователя.
     */


    /**
     * Получить собеседника для данного чата.
     */
    public function getInterlocutorForChat(Chat $chat)
    {
        if ($chat->user_id_sender == $this->id) {
            return User::find($chat->user_id_recipient);
        } else {
            return User::find($chat->user_id_sender);
        }
    }

    /**
     * Получить все активные чаты пользователя.
     */
    public function activeChats()
    {
        return $this->chats()->has('messages')->latest('updated_at');
    }

    /**
     * Получить последнее сообщение для каждого чата пользователя.
     */
    public function getChatsWithLastMessage()
    {
        return $this->chats()
            ->with(['messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function ($chat) {
                $chat->lastMessage = $chat->messages->first();
                $chat->interlocutor = $this->getInterlocutorForChat($chat);
                return $chat;
            });
    }

    /**
     * Создать новый чат с другим пользователем.
     */
    public function startChatWith(User $recipient)
    {
        return Chat::create([
            'user_id_sender' => $this->id,
            'user_id_recipient' => $recipient->id,
        ]);
    }

    /**
     * Отправить сообщение в чат.
     */
    public function sendMessage(Chat $chat, string $content)
    {
        return $chat->messages()->create([
            'user_id' => $this->id,
            'content' => $content,
        ]);
    }

    /**
     * Получить непрочитанные сообщения для пользователя.
     */
    public function unreadMessages()
    {
        return Message::whereHas('chat', function ($query) {
            $query->where('user_id_recipient', $this->id);
        })->where('is_read', false);
    }
}