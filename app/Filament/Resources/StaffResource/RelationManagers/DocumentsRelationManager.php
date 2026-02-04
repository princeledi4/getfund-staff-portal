<?php

namespace App\Filament\Resources\StaffResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\RelationManagers\RelationManager;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Documents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('document_type')
                    ->options([
                        'Contract' => 'Employment Contract',
                        'Certificate' => 'Certificate',
                        'ID' => 'Identification Document',
                        'License' => 'Professional License',
                        'Qualification' => 'Academic Qualification',
                        'Medical' => 'Medical Certificate',
                        'Police Clearance' => 'Police Clearance',
                        'Reference' => 'Reference Letter',
                        'Other' => 'Other',
                    ])
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Employment Contract 2024'),

                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->placeholder('Optional description...'),

                Forms\Components\FileUpload::make('file_path')
                    ->label('Document File')
                    ->required()
                    ->disk('public')
                    ->directory('staff-documents')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])
                    ->maxSize(5120) // 5MB
                    ->downloadable()
                    ->openable()
                    ->previewable(true)
                    ->helperText('Accepted: PDF, JPG, PNG (Max 5MB)'),

                Forms\Components\DatePicker::make('issue_date')
                    ->label('Issue Date')
                    ->placeholder('Select issue date')
                    ->displayFormat('d/m/Y')
                    ->native(false),

                Forms\Components\DatePicker::make('expiry_date')
                    ->label('Expiry Date')
                    ->placeholder('Select expiry date (if applicable)')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->afterOrEqual('issue_date'),

                Forms\Components\Toggle::make('is_verified')
                    ->label('Mark as Verified')
                    ->helperText('Check if this document has been verified')
                    ->inline(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_type')
                    ->badge()
                    ->colors([
                        'primary' => 'Contract',
                        'success' => 'Certificate',
                        'warning' => 'ID',
                        'danger' => 'License',
                        'info' => 'Qualification',
                    ])
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('file_name')
                    ->label('File')
                    ->formatStateUsing(fn ($state) => $state ?? 'N/A')
                    ->limit(25)
                    ->tooltip(fn ($record) => $record->file_name),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Expires')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->isExpired() ? 'danger' : ($record->isExpiringSoon() ? 'warning' : 'success'))
                    ->icon(fn ($record) => $record->isExpired() ? 'heroicon-o-x-circle' : ($record->isExpiringSoon() ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle'))
                    ->placeholder('No expiry'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('document_type')
                    ->options([
                        'Contract' => 'Employment Contract',
                        'Certificate' => 'Certificate',
                        'ID' => 'Identification Document',
                        'License' => 'Professional License',
                        'Qualification' => 'Academic Qualification',
                        'Medical' => 'Medical Certificate',
                        'Police Clearance' => 'Police Clearance',
                        'Reference' => 'Reference Letter',
                        'Other' => 'Other',
                    ]),

                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Verification Status')
                    ->placeholder('All documents')
                    ->trueLabel('Verified only')
                    ->falseLabel('Unverified only'),

                Tables\Filters\Filter::make('expiring_soon')
                    ->label('Expiring Soon')
                    ->query(fn ($query) => $query->where('expiry_date', '>=', now())->where('expiry_date', '<=', now()->addDays(30))),

                Tables\Filters\Filter::make('expired')
                    ->label('Expired')
                    ->query(fn ($query) => $query->where('expiry_date', '<', now())),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        if (isset($data['file_path'])) {
                            $filePath = $data['file_path'];
                            $fullPath = Storage::disk('public')->path($filePath);

                            if (file_exists($fullPath)) {
                                $data['file_name'] = basename($filePath);
                                $data['file_type'] = pathinfo($filePath, PATHINFO_EXTENSION);
                                $data['file_size'] = filesize($fullPath);
                            }
                        }

                        if ($data['is_verified'] ?? false) {
                            $data['verified_at'] = now();
                            $data['verified_by'] = auth()->id();
                        }

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data, $record) {
                        if (($data['is_verified'] ?? false) && !$record->is_verified) {
                            $data['verified_at'] = now();
                            $data['verified_by'] = auth()->id();
                        } elseif (!($data['is_verified'] ?? false)) {
                            $data['verified_at'] = null;
                            $data['verified_by'] = null;
                        }

                        return $data;
                    }),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->url(fn ($record) => Storage::disk('public')->url($record->file_path))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
