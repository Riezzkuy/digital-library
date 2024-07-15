<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->live(debounce: 300)
                    ->label('Title')
                    ->required()
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if($operation === 'edit') {
                            return;
                        }

                        $set('slug',  Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord:true)
                    ->readOnly()
                    ->maxLength(150),
                TextInput::make('isbn')
                    ->label('ISBN')
                    ->required()
                    ->maxLength(13),
                TextInput::make('publication_year')
                    ->label('Publication Year')
                    ->required()
                    ->maxLength(4),
                Select::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->required(),
                Select::make('categories')
                    ->label('Category')
                    ->multiple()
                    ->relationship(titleAttribute: 'name')
                    ->searchable()
                    ->required(),
                FileUpload::make('cover')
                    ->image()
                    ->directory('books/covers')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                FileUpload::make('file')
                    ->label('File')
                    ->directory('books/files')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
