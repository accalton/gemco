<?php

namespace App\Filament\Resources\Memberships\Tables;

use App\Models\Membership;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MembershipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Columns\TextColumn::make('type')
                    ->formatStateUsing(fn (string $state): string => Membership::TYPES[$state]),
                Columns\TextColumn::make('members.name')
                    ->bulleted()
                    ->searchable(),
                Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Membership::STATUS_ACTIVE    => 'success',
                        Membership::STATUS_CANCELLED => 'danger',
                        Membership::STATUS_EXPIRED   => 'danger',
                        Membership::STATUS_PENDING   => 'warning',
                        Membership::STATUS_REVOKED   => 'gray'
                    }),
                Columns\TextColumn::make('expiry')
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
