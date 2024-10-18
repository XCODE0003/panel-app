<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Chat;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Профиль';
    protected static ?string $title = 'Профиль';
    protected static ?string $modelLabel = 'Профиль';
    protected static ?string $pluralLabel = 'Профили';

    protected static string $view = 'filament.pages.profile';

    public ?User $user = null;
    public ?array $avatarData = [];
    public ?array $settingsData = [];

    public function mount(?User $user = null): void
    {
        $this->user = $user ?? auth()->user();

        $this->fillForms();
    }
    public static function getNavigationUrl(): string
    {
        return static::getUrl(['user' => auth()->id()]);
    }


    protected function getForms(): array
    {
        return [
            'avatarForm' => $this->makeForm()
                ->schema([
                    FileUpload::make('avatar')
                        ->label('')
                        ->avatar()
                        ->required()
                        ->visible(fn() => $this->user->id === auth()->id()),
                ])
                ->statePath('avatarData'),

            'settingsForm' => $this->makeForm()
                ->schema([
                    TextInput::make('login')
                        ->label('Логин')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->disabled(!auth()->user()->is_admin),
                    TextInput::make('telegram_username')
                        ->label('Username в Telegram'),
                    TextInput::make('password_old')
                        ->label('Старый пароль')
                        ->password()->disabled(auth()->user()->is_admin),
                    TextInput::make('password')
                        ->label('Новый пароль')
                        ->password(),
                    Toggle::make('is_banned')
                        ->label('Заблокировать пользователя')
                        ->onIcon('heroicon-m-lock-closed')
                        ->offIcon('heroicon-m-lock-open')
                        ->onColor('danger')
                        ->offColor('success')
                        ->inline()
                        ->hidden(!auth()->user()->is_admin),


                ])
                ->statePath('settingsData'),
        ];
    }
    public function openChat(): void
    {
        $chat = Chat::where('user_id_sender', auth()->user()->id)
            ->where('user_id_recipient', $this->user->id)
            ->first();
        if (!$chat) {
            $chat = Chat::create([
                'user_id_sender' => auth()->user()->id,
                'user_id_recipient' => $this->user->id,
                'is_group' => false,
            ]);
        }
        $this->redirect('/user/chat/' . $chat->id);
    }

    public function fillForms(): void
    {
        $this->avatarForm->fill([
            'avatar' => $this->user->avatar_url,
        ]);
        $this->settingsForm->fill([
            'telegram_username' => $this->user->telegram_username,
            'login' => $this->user->login,
        ]);
    }

    public function updateAvatar(): void
    {
        $data = $this->avatarForm->getState();

        if ($data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['avatar']->store('avatars', 'public');
        } else {
            $path = $data['avatar'];
        }

        $this->user->update(['avatar_url' => $path]);

        Notification::make()
            ->title('Аватар обновлен')
            ->success()
            ->send();
    }

    public function updateSettings(): void
    {
        $data = $this->settingsForm->getState();
        if ($data['password'] && auth()->user()->is_admin) {
            $password = Hash::make($data['password']);
            $this->user->update(['password' => $password]);
            Notification::make()
                ->title('Пароль обновлен')
                ->success()
                ->send();
            return;
        }
        if (!auth()->user()->is_admin && ($data['password_old'] && $data['password'])) {
            if (Hash::check($data['password_old'], $this->user->password)) {
                $this->user->update(['password' => Hash::make($data['password'])]);
            } else {
                Notification::make()
                    ->title('Неверный старый пароль')
                    ->danger()
                    ->send();
                return;
            }
        }
        if (!$data['password']) {
            unset($data['password']);
        }
        $this->user->update($data);

        Notification::make()
            ->title('Настройки обновлены')
            ->success()
            ->send();
    }

    public static function getSlug(): string
    {
        return 'profile/{user?}';
    }
}