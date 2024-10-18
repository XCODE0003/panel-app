<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateResource\Pages;
use App\Filament\Resources\TemplateResource\RelationManagers;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\ImageUpload;
use Rupadana\FilamentSwiper\Infolists\Components\SwiperImageEntry;
use Filament\Infolists\Components\ViewEntry;
use Illuminate\Database\Eloquent\Model;

class TemplateResource extends Resource
{

    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Шаблоны';
    protected static ?string $title = 'Шаблоны';
    protected static ?string $modelLabel = 'Шаблон';
    protected static ?string $pluralLabel = 'Шаблоны';

    public static function canCreate(): bool
    {
        return auth()->user()->is_admin;
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->is_admin;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->label('Название'),
                FileUpload::make('images')->multiple()->required()->label('Изображения'),
                Textarea::make('description')->required()->label('Описание'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    ImageColumn::make('images')->label('Изображения')->height(200)->alignCenter()->limit(1),
                    TextColumn::make('name')->label('Название'),
                    TextColumn::make('description')->label('Описание'),
                ])
            ])->selectable(false)
            ->contentGrid([
                'xs' => 1,
                'md' => 2,
                'xl' => 2,
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->record(fn($record) => $record)
                    ->modalContent(fn($record) => view('filament.modal.slider', ['record' => $record]))
                    ->form([])
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
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}