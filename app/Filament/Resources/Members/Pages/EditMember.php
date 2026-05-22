<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Models\Member;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('View Membership')
                ->url(function (Member $record) {
                    if ($record->membership) {
                        $id = $record->membership->id;
                    } elseif ($record->memberships->count()) {
                        $id = $record->memberships()->first()->id;
                    }

                    if ($id) {
                        return route('filament.admin.resources.memberships.edit', ['record' => $id]);
                    }
                })
                ->hidden(function (Member $record) {
                    if ($record->membership || $record->memberships->count()) {
                        return false;
                    }

                    return true;
                }),
            DeleteAction::make(),
        ];
    }
}
