<?php

namespace App\Filament\Resources\Memberships\Tables;

use App\Models\Membership;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                    ->state(function ($record) {
                        $names = [];
                        
                        foreach ($record->members as $member) {
                            $names[] = $member->name;
                        }

                        foreach ($record->users as $member) {
                            $names[] = $member->name;
                        }

                        sort($names);

                        return $names;
                    })
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
