<?php

namespace App\Filament\Exports;

use App\Models\Membership;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class MembershipExporter extends Exporter
{
    protected static ?string $model = Membership::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('type'),
            ExportColumn::make('status'),
            ExportColumn::make('expiry'),
            ExportColumn::make('address.full_address'),
            ExportColumn::make('members.name')
                ->label('Members')
                ->listAsJson(),
            ExportColumn::make('members.date_of_birth')
                ->label('Date of Birth')
                ->listAsJson(),
            ExportColumn::make('members.contact_email')
                ->label('Email Address')
                ->listAsJson(),
            ExportColumn::make('members.phone')
                ->label('Phone Number')
                ->listAsJson(),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your membership export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
