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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Main Content')
                    ->schema([
                        TextInput::make('title')
                            ->lazy()
                            ->label('Title')
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
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),
                        Select::make('publisher_id')
                            ->label('Publisher')
                            ->relationship('publisher', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('authors')
                            ->label('Author')
                            ->multiple()
                            ->relationship('authors', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('categories')
                            ->label('Category')
                            ->multiple()
                            ->relationship(titleAttribute: 'name')
                            ->searchable()
                            ->required(),
                        TextInput::make('description')
                            ->required(),
                    ]),
                Section::make('Meta')
                    ->schema([
                        FileUpload::make('cover')
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
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('publication_year')
                    ->label('Publication Year')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge(),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('cover')
                    ->label('Cover'),
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
