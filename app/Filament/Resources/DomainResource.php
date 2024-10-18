<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DomainResource\Pages;
use App\Filament\Resources\DomainResource\RelationManagers;
use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Actions\Action;
use App\Services\Domain\CreateDomain;
use Filament\Notifications\Notification;
use Exception;
use Filament\Tables\Actions\ActionGroup;
use Filament\Support\Enums\FontFamily;

class DomainResource extends Resource
{

    protected static ?string $model = Domain::class;
    protected static ?string $navigationLabel = 'Домены';
    protected static ?string $title = 'Домены';
    protected static ?string $modelLabel = 'Домены';
    protected static ?string $pluralLabel = 'Домены';
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('domain')->hiddenOn('edit'),

                Select::make('template_id')
                    ->options([
                        0 => 'Шаблон 1',
                        1 => 'Шаблон 2',
                        2 => 'Шаблон 3',
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('domain')->searchable()->label('Домен'),
                TextColumn::make('template_id')->label('Шаблон')->formatStateUsing(fn($state) => match ($state) {
                    0 => 'Шаблон 1',
                    1 => 'Шаблон 2',
                    2 => 'Шаблон 3',
                }),
                TextColumn::make('ns_records')->label('NS записи')->html()->getStateUsing(fn($record) => implode("<br>", $record->ns_records))->fontFamily(FontFamily::Mono)->size('sm')->copyableState(fn($record) => implode(", ", $record->ns_records))->badge()->color('gray')->copyable(),
                TextColumn::make('status')->label('Статус')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'active' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Настроить'),
                    Tables\Actions\DeleteAction::make()->label('Отвязать'),
                ])->color('gray')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('createCustomDomain')
                    ->modalWidth('sm')
                    ->label('Привязать домен')->form([
                        TextInput::make('domain')->required()->label('Домен'),
                        Select::make('template_id')
                            ->options([
                                0 => 'Шаблон 1',
                                1 => 'Шаблон 2',
                                2 => 'Шаблон 3',
                            ])->required()->label('Шаблон'),
                    ])->action(function (array $data) {
                        try {
                            $domain = (new CreateDomain())->create($data);
                            Notification::make()
                                ->title('Домен успешно создан')
                                ->success()
                                ->send();
                            return $domain;
                        } catch (Exception $e) {
                            Notification::make()
                                ->title('Ошибка при создании домена')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })->modalSubmitActionLabel('Привязать'),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', auth()->id()));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
