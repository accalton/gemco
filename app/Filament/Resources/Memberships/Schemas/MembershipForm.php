<?php

namespace App\Filament\Resources\Memberships\Schemas;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Membershipable;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class MembershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make()
                        ->schema([
                            Repeater::make('members')
                                ->columnSpan(4)
                                ->collapsed()
                                ->itemLabel(function (array $state) {
                                    $label = [];
                                    $array = explode(':', $state['membershipable_id']);

                                    if (isset($array[1])) {
                                        $member = $array[0]::find($array[1]);
                                        $label[] = $member->name;
                                    }

                                    return implode(' - ', $label);
                                })
                                ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                                    $data['membershipable_id'] = $data['membershipable_type'] . ':' . $data['membershipable_id'];

                                    return $data;
                                })
                                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                    return self::mutateMembershipableData($data);
                                })
                                ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                    return self::mutateMembershipableData($data);
                                })
                                ->relationship(name: 'membershipable')
                                ->orderColumn('order')
                                ->schema([
                                    Flex::make([
                                        Select::make('membershipable_id')
                                            ->columnSpan(3)
                                            ->label('Member')
                                            ->live()
                                            ->options(function ($record) {
                                                $options = [];
                                                foreach ([Member::class, User::class] as $class) {
                                                    $members = $class::query()
                                                        ->whereRelation('membershipables', 'id', $record->id ?? null)
                                                        ->orDoesntHave('membershipables')
                                                        ->get()
                                                        ->pluck('name', 'id');
                                                    foreach ($members as $key => $name) {
                                                        $key = $class . ':' . $key;
                                                        $options[$key] = $name;
                                                    }
                                                }

                                                asort($options);
    
                                                return $options;
                                            }),
                                        Radio::make('type')
                                            ->grow(false)
                                            ->inline()
                                            ->options(Membershipable::TYPES)
                                            ->required(),
                                    ])
                                ])
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

    /**
     * @param array $data
     *
     * @return array
     */
    private static function mutateMembershipableData(array $data): array
    {
        $array = explode(':', $data['membershipable_id']);
        $data['membershipable_id'] = $array[1];
        $data['membershipable_type'] = $array[0];

        return $data;
    }
}
