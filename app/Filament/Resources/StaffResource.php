<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Staff;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StaffResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StaffResource\RelationManagers;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('middle_name')
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('staff_id')
                            ->label('Staff ID')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('position')
                            ->required()
                            ->maxLength(255),

                        Select::make('department_id')
                            ->label('Department')
                            ->required()
                            ->options(Department::pluck('name', 'id')->toArray())
                            ->relationship('department', 'name')
                            ->preload()
                            ->searchable(),

                        FileUpload::make('photo')
                            ->required()
                            ->directory('staff')
                            ->imageEditor()
                            ->columnSpanFull()
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'view' => Pages\ViewStaff::route('/{record}'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
