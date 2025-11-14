<?php

namespace App\Filament\Resources\Memberships\Schemas;

use App\Models\Membership;
use App\Models\MembershipUser;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class MembershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make()
                        ->schema([
                            Repeater::make('membershipUsers')
                                ->addActionLabel('Add member / contact')
                                ->collapsed()
                                ->itemLabel(function (Schema $item) {
                                    if ($item->model->type ?? false) {
                                        $type = MembershipUser::TYPES[$item->model->type];
                                        $name = $item->model->user->name ?? null;
                                        return $type . ': ' . $name;
                                    }

                                    return null;
                                })
                                ->label('Members & Contacts')
                                ->relationship()
                                ->schema([
                                    Flex::make([
                                        Select::make('user_id')
                                            ->relationship(
                                                name: 'user',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn (Builder $query, ?MembershipUser $record) => $query
                                                    ->whereRelation('membershipUsers', 'id', $record->id ?? null)
                                                    ->orDoesntHave('membershipUsers'),
                                            )
                                            ->required(),
                                        Radio::make('type')
                                            ->inline()
                                            ->options(MembershipUser::TYPES)
                                            ->required()
                                            ->grow(false)
                                    ])
                                ])
                                ->orderColumn('order')
                        ])->columnSpanFull(),
                    Section::make()
                        ->schema([
                            TextInput::make('id')
                                ->disabled()
                                ->readOnly(),
                            Select::make('type')
                                ->options(Membership::TYPES)
                                ->required(),
                            Select::make('status')
                                ->options(Membership::STATUSES)
                                ->required(),
                            DatePicker::make('expiry')
                                ->required()
                        ])->grow(false)
                ])->from('md')->columnSpanFull()
            ]);
    }
}
