<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Filament\Resources\BookResource\RelationManagers\CopiesRelationManager;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getModelLabel(): string
    {
        return __('Book');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Books');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Main Content')
                    ->schema([
                        TextInput::make('title')
                            ->lazy()
                            ->translateLabel()
                            ->required()
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'edit') {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->readOnly(),
                        TextInput::make('isbn')
                            ->required()
                            ->unique(ignoreRecord: true),
                        DatePicker::make('published_at')
                            ->translateLabel()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),
                        Select::make('publisher')
                            ->translateLabel()
                            ->relationship('publisher', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('authors')
                            ->translateLabel()
                            ->multiple()
                            ->relationship('authors', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('categories')
                            ->translateLabel()
                            ->multiple()
                            ->relationship(titleAttribute: 'name')
                            ->searchable()
                            ->required(),
                        TextInput::make('description')
                            ->translateLabel()
                            ->required(),
                        TextInput::make('pages')
                            ->translateLabel()
                            ->type('number')
                            ->required(),
                    ]),
                Section::make('Meta')
                    ->schema([
                        FileUpload::make('cover')
                            ->translateLabel()
                            ->image()
                            ->directory('books/covers')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('authors.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('categories.name')
                    ->translateLabel()
                    ->badge(),
                TextColumn::make('description')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pages')
                    ->translateLabel()
                    ->sortable(),
                ImageColumn::make('cover')
                    ->translateLabel(),
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
            CopiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
