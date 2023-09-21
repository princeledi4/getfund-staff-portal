<?php

namespace App\Filament\Resources\DepartmentResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Staff;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class StaffsRelationManager extends RelationManager
{
    protected static string $relationship = 'staffs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('staff_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('staff_id')
            ->columns([
                ImageColumn::make('photo')
                    ->circular(),

                TextColumn::make('first_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, Staff $staff) {
                        return $staff->first_name . ' ' . $staff->middle_name . ' ' . $staff->last_name;
                    }),

                TextColumn::make('staff_id')
                    ->copyable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('department.name')
                ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->relationship('department', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
