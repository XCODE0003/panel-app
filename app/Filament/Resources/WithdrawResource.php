<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawResource\Pages;
use App\Filament\Resources\WithdrawResource\RelationManagers;
use App\Models\Withdraw;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\User;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WithdrawResource extends Resource
{
    protected static ?string $navigationLabel = 'Выводы';
    protected static ?string $title = 'Выводы';
    protected static ?string $modelLabel = 'Выводы';
    protected static ?string $pluralLabel = 'Выводы';

    protected static ?string $model = Withdraw::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';


    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Пользователь')
                    ->options(User::all()->pluck('login', 'id')),
                TextInput::make('amount')
                    ->label('Сумма'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')->label('Пользователь')->formatStateUsing(fn($record) => User::find($record->user_id)->login),
                TextColumn::make('amount')->label('Сумма'),
                TextColumn::make('created_at')->label('Дата создания')->dateTime('d.m.Y H:i')->sortable(),

            ])
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
            'index' => Pages\ListWithdraws::route('/'),
            'create' => Pages\CreateWithdraw::route('/create'),
            'edit' => Pages\EditWithdraw::route('/{record}/edit'),
        ];
    }
}
