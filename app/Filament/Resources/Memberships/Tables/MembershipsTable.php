<?php

namespace App\Filament\Resources\Memberships\Tables;

use App\Filament\Exports\MembershipExporter;
use App\Models\Membership;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Tables\Columns;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MembershipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('type')
                    ->formatStateUsing(fn (string $state): string => Membership::TYPES[$state]),
                TextColumn::make('members.name')
                    ->bulleted()
                    ->label('Members')
                    ->searchable(),
                TextColumn::make('contacts.name')
                    ->bulleted()
                    ->label('Contacts')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Membership::STATUS_ACTIVE    => 'success',
                        Membership::STATUS_CANCELLED => 'danger',
                        Membership::STATUS_EXPIRED   => 'danger',
                        Membership::STATUS_PENDING   => 'warning',
                        Membership::STATUS_REVOKED   => 'gray',
                    })
                    ->state(fn (Membership $record) => $record->isExpired ? 'expired' : $record->status),
                TextColumn::make('expiry')
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->options(Membership::TYPES),
                SelectFilter::make('status')
                    ->options(Membership::STATUSES),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                ExportAction::make()
                    ->exporter(MembershipExporter::class),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
