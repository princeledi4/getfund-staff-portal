<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use Filament\Forms\Components\Textarea;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->minLength(3)
                    ->maxLength(255)
                    ->columnspanFull(),

                Textarea::make('description')
                    ->columnspanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDepartments::route('/'),
            'view' => Pages\ViewDepartment::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StaffsRelationManager::class,
        ];
    }
}
