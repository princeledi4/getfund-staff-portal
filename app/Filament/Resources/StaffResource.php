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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StaffImport;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
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
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        FileUpload::make('photo')
                            ->required()
                            ->maxSize(10240)
                            ->disk('public')
                            ->directory('staff')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('1:1')
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                                '16:9',
                            ])
                            ->helperText('Upload a photo (up to 10 MB). Large images will be automatically compressed to under 2 MB.')
                            ->columnSpanFull()
                    ])
                    ->columns(2),

                Section::make('Employment Details')
                    ->schema([
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

                        Select::make('employment_type')
                            ->required()
                            ->options([
                                'Full-time' => 'Full-time',
                                'Part-time' => 'Part-time',
                                'Contract' => 'Contract',
                                'Intern' => 'Intern',
                            ])
                            ->default('Full-time'),

                        Forms\Components\DatePicker::make('date_joined')
                            ->label('Date Joined')
                            ->required()
                            ->default(now()),

                        Select::make('status')
                            ->required()
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active'),

                        Forms\Components\DatePicker::make('valid_until')
                            ->label('Valid Until')
                            ->required()
                            ->default(now()->addYear()),
                    ])
                    ->columns(2),

                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone_number')
                            ->tel()
                            ->maxLength(16),

                        TextInput::make('location')
                            ->label('Location (City/Region)')
                            ->maxLength(255)
                            ->placeholder('e.g., Accra, Greater Accra'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->disk('public')
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
                Tables\Actions\Action::make('import')
                    ->label('Import Staff')
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
                            ->helperText('Upload an Excel or CSV file with staff data. Required columns: staff_id, first_name, last_name'),
                    ])
                    ->action(function (array $data) {
                        try {
                            $import = new StaffImport;
                            $filePath = storage_path('app/public/' . $data['file']);
                            Excel::import($import, $filePath);

                            $errors = $import->getErrors();
                            $failures = $import->getFailures();

                            if (empty($errors) && empty($failures)) {
                                Notification::make()
                                    ->title('Import successful')
                                    ->body('Staff data has been imported successfully.')
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
                            ? 'staff_import_template.xlsx'
                            : 'staff_import_template.csv';
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Personal Information')
                    ->schema([
                        ImageEntry::make('photo')
                            ->disk('public')
                            ->circular()
                            ->size(150)
                            ->columnSpanFull(),

                        TextEntry::make('first_name')
                            ->label('First Name'),

                        TextEntry::make('middle_name')
                            ->label('Middle Name'),

                        TextEntry::make('last_name')
                            ->label('Last Name'),

                        TextEntry::make('staff_id')
                            ->label('Staff ID')
                            ->copyable(),
                    ])
                    ->columns(2),

                InfoSection::make('Employment Details')
                    ->schema([
                        TextEntry::make('position')
                            ->label('Position'),

                        TextEntry::make('department.name')
                            ->label('Department'),

                        TextEntry::make('employment_type')
                            ->label('Employment Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Full-time' => 'success',
                                'Part-time' => 'warning',
                                'Contract' => 'info',
                                'Intern' => 'gray',
                                default => 'gray',
                            }),

                        TextEntry::make('date_joined')
                            ->label('Date Joined')
                            ->date(),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'gray',
                                'suspended' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('valid_until')
                            ->label('Valid Until')
                            ->date(),
                    ])
                    ->columns(2),

                InfoSection::make('Contact Information')
                    ->schema([
                        TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-m-envelope')
                            ->copyable(),

                        TextEntry::make('phone_number')
                            ->label('Phone Number')
                            ->icon('heroicon-m-phone')
                            ->copyable(),

                        TextEntry::make('location')
                            ->label('Location')
                            ->icon('heroicon-m-map-pin'),
                    ])
                    ->columns(2),
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
