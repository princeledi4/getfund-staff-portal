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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DepartmentImport;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

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
            ->headerActions([
                Tables\Actions\Action::make('import')
                    ->label('Import Departments')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        FileUpload::make('file')
                            ->label('Excel/CSV File')
                            ->acceptedFileTypes([
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/csv'
                            ])
                            ->required()
                            ->helperText('Upload an Excel or CSV file with department data. Required columns: name'),
                    ])
                    ->action(function (array $data) {
                        try {
                            $import = new DepartmentImport;
                            $filePath = storage_path('app/public/' . $data['file']);
                            Excel::import($import, $filePath);

                            $errors = $import->getErrors();
                            $failures = $import->getFailures();

                            if (empty($errors) && empty($failures)) {
                                Notification::make()
                                    ->title('Import successful')
                                    ->body('Department data has been imported successfully.')
                                    ->success()
                                    ->send();
                            } else {
                                $errorMessages = array_merge(
                                    $errors,
                                    array_map(fn($failure) => "Row {$failure->row()}: " . implode(', ', $failure->errors()), $failures)
                                );

                                Notification::make()
                                    ->title('Import completed with errors')
                                    ->body('Some rows failed to import: ' . implode('; ', array_slice($errorMessages, 0, 3)))
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Import failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('downloadTemplate')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->form([
                        Select::make('format')
                            ->label('Choose Format')
                            ->options([
                                'csv' => 'CSV Format',
                                'excel' => 'Excel Format (.xlsx)',
                            ])
                            ->default('csv')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $format = $data['format'] ?? 'csv';
                        $filename = $format === 'excel'
                            ? 'department_import_template.xlsx'
                            : 'department_import_template.csv';
                        $path = storage_path('app/import-templates/' . $filename);

                        return response()->download($path, $filename);
                    }),
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
