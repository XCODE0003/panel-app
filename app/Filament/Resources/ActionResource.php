<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers;
use App\Models\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Логи';
    protected static ?string $title = 'Логи';
    protected static ?string $modelLabel = 'Логи';
    protected static ?string $pluralLabel = 'Логи';
    public static function canCreate(): bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
    protected static function getDevice(Action $record): string
    {
        $userAgent = $record->user_agent;
        $device = 'Unknown';

        if (strpos($userAgent, 'Mobile') !== false) {
            $device = 'Mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            $device = 'Tablet';
        }

        return $device;
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Тип')->badge()->sortable()->color(
                    fn(string $state): string => match ($state) {
                        'connectWallet' => 'success',
                        'aprovedTransaction' => 'info',
                        'transactionRequest' => 'danger',
                        'fee' => 'danged',
                        default => 'secondary',
                    }
                ),
                Tables\Columns\TextColumn::make('domain')->label('Домен')->searchable(),
                Tables\Columns\TextColumn::make('ip')->label('IP')->searchable(),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('Устройство')
                    ->formatStateUsing(fn(Action $record): string => self::getDevice($record)),
                Tables\Columns\TextColumn::make('address')->label('Адрес'),
                Tables\Columns\TextColumn::make('balance')->label('Баланс'),
                Tables\Columns\TextColumn::make('country')->label('Страна')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Дата создания'),
            ])
            ->modifyQueryUsing(fn(Builder $query) => auth()->user()->is_admin ? $query : $query->where('user_id', auth()->id()))
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListActions::route('/'),
            'create' => Pages\CreateAction::route('/create'),
            'edit' => Pages\EditAction::route('/{record}/edit'),
        ];
    }
}
