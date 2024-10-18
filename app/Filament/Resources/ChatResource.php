<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatResource\Pages;
use App\Filament\Resources\ChatResource\RelationManagers;
use App\Models\Chat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Беседы';
    protected static ?string $title = 'Беседа';
    protected static ?string $modelLabel = 'Беседа';
    protected static ?string $pluralLabel = 'Беседы';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Название'),
                FileUpload::make('avatar')->image()->label('Аватар'),
                Toggle::make('allow_messages')->label('Разрешить сообщения')->inline(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Название'),
                ImageColumn::make('avatar')->label('Аватар'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d.m.Y H:i')->label('Дата создания'),
                Tables\Columns\ToggleColumn::make('allow_messages')->label('Разрешить сообщения'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')->label('Создать беседу')->form(fn() => [
                    TextInput::make('name'),
                    FileUpload::make('avatar'),
                    Toggle::make('allow_messages')->label('Разрешить сообщения')->inline(true),
                ])->action(fn($data) => self::createChat($data)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->modifyQueryUsing(function (Builder $query) {
                return $query->where('is_group', true);
            });
    }
    private static function createChat(array $data): Chat
    {
        $data['is_group'] = true;
        return Chat::create($data);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChats::route('/'),
            'create' => Pages\CreateChat::route('/create'),
            'edit' => Pages\EditChat::route('/{record}/edit'),
        ];
    }
}
