<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function getModelLabel(): string
    {
        return __('History');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Histories');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('copy.book.title')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('copy.call_number')
                    ->label(__('Call Number'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.email')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('loaned_at')
                    ->translateLabel(),
                TextColumn::make('returned_at')
                    ->translateLabel(),
                TextColumn::make('status')
                    ->translateLabel()
                    ->getStateUsing(function ($record) {
                        if ($record->loaned_at && !$record->returned_at) {
                            return __('Loaned');
                        } elseif ($record->loaned_at && $record->returned_at) {
                            return __('Returned');
                        } else {
                            return __('Queued');
                        }
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        __('Queued') => 'gray',
                        __('Loaned') => 'warning',
                        __('Returned') => 'success'
                    }),
            ])
            ->filters([
                Filter::make('status')
                    ->label('Status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'Queued' => 'Queued',
                                'Loaned' => 'Loaned',
                                'Returned' => 'Returned',
                            ])
                            ->native(false)
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['status']) {
                            $query->where(function ($query) use ($data) {
                                if ($data['status'] === 'Queued') {
                                    $query->whereNull('loaned_at')->whereNull('returned_at');
                                } elseif ($data['status'] === 'Loaned') {
                                    $query->whereNotNull('loaned_at')->whereNull('returned_at');
                                } elseif ($data['status'] === 'Returned') {
                                    $query->whereNotNull('loaned_at')->whereNotNull('returned_at');
                                }
                            });
                        }
                    }),
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
            'index' => Pages\ListLoans::route('/'),
        ];
    }
}
