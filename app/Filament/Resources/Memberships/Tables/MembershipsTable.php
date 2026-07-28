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
use Filament\Tables\Filters\Filter;
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
                    ->searchable(),
                TextColumn::make('members.contact_email')
                    ->bulleted()
                    ->label('Member Email Addresses')
                    ->state(function (Membership $record) {
                        if ($record->isExpired) {
                            return null;
                        }

                        $emails = [];
                        foreach ($record->members as $member) {
                            $emails[] = $member->contact_email;
                        }

                        return $emails;
                    }),
                TextColumn::make('members.phone')
                    ->bulleted()
                    ->label('Member Phone Numbers')
                    ->state(function (Membership $record) {
                        if ($record->isExpired) {
                            return null;
                        }

                        $phoneNumbers = [];
                        foreach ($record->members as $member) {
                            $phoneNumbers[] = $member->phone;
                        }

                        return $phoneNumbers;
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Membership::STATUSES[Membership::STATUS_ACTIVE]    => 'success',
                        Membership::STATUSES[Membership::STATUS_CANCELLED] => 'danger',
                        Membership::STATUSES[Membership::STATUS_PENDING]   => 'warning',
                        Membership::STATUSES[Membership::STATUS_REVOKED]   => 'gray',
                    })
                    ->state(fn (Membership $record) => Membership::STATUSES[$record->status]),
                TextColumn::make('isExpired')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Current' => 'success',
                        'Expired' => 'danger',
                    })
                    ->label('Expired')
                    ->state(fn (Membership $record) => $record->isExpired ? 'Expired' : 'Current'),
                TextColumn::make('expiry')
                    ->date('jS F, Y')
                    ->sortable(),
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
