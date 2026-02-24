<?php

namespace App\Filament\Resources\Memberships\Schemas;

use App\Models\Address;
use App\Models\MemberMembership;
use App\Models\Membership;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
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
                            Select::make('member_id')
                                ->createOptionForm(self::memberForm(true))
                                ->editOptionForm(self::memberForm(true))
                                ->preload()
                                ->relationship(
                                    modifyQueryUsing: function (Builder $query, ?Membership $record) {
                                        return $query
                                            ->whereDoesntHave('membership')
                                            ->whereDoesntHave('member_memberships')
                                            ->orWhereRelation('membership', 'id', $record->id ?? null);
                                    },
                                    name: 'member',
                                    titleAttribute: 'name'
                                )
                                ->required()
                                ->searchable(),
                            Repeater::make('members')
                                ->hidden(fn (Get $get): bool => $get('type') !== Membership::TYPE_FAMILY)
                                ->defaultItems(0)
                                ->label('Additional Members')
                                ->mutateRelationshipDataBeforeCreateUsing(fn (array $data) => array_merge($data, ['type' => MemberMembership::TYPE_MEMBER]))
                                ->relationship(
                                    modifyQueryUsing: fn (Builder $query): Builder => $query->where('type', 'member'),
                                    name: 'member_memberships'
                                )
                                ->simple(
                                    Select::make('member_id')
                                        ->createOptionForm(self::memberForm(true))
                                        ->editOptionForm(self::memberForm(true))
                                        ->preload()
                                        ->relationship(
                                            modifyQueryUsing: fn (Builder $query, ?MemberMembership $record): Builder => $query
                                                ->whereDoesntHave('member_memberships')
                                                ->orWhereRelation('member_memberships', 'id', $record->id ?? null),
                                            name: 'member',
                                            titleAttribute: 'name'
                                        )
                                        ->required()
                                        ->searchable(),
                                )
                                ->orderColumn('order'),
                            Repeater::make('contacts')
                                ->defaultItems(0)
                                ->label('Contacts')
                                ->mutateRelationshipDataBeforeCreateUsing(fn (array $data) => array_merge($data, ['type' => MemberMembership::TYPE_CONTACT]))
                                ->relationship(
                                    modifyQueryUsing: fn (Builder $query): Builder => $query->where('type', 'contact'),
                                    name: 'member_memberships'
                                )
                                ->schema([
                                    Select::make('member_id')
                                        ->createOptionForm(self::memberForm())
                                        ->editOptionForm(self::memberForm())
                                        ->preload()
                                        ->relationship(
                                            modifyQueryUsing: fn (Builder $query, ?MemberMembership $record): Builder => $query
                                                ->whereDoesntHave('member_memberships')
                                                ->orWhereRelation('member_memberships', 'id', $record->id ?? null),
                                            name: 'member',
                                            titleAttribute: 'name'
                                        )
                                        ->required()
                                        ->searchable(),
                                    TextInput::make('relationship'),
                                ])
                                ->orderColumn('order'),
                            Fieldset::make('Address')
                                ->columns(6)
                                ->contained(false)
                                ->relationship(
                                    'address',
                                    condition: function (?array $state): bool {
                                        foreach ([
                                            'line1',
                                            'line2',
                                            'suburb',
                                            'postcode',
                                            'state'
                                        ] as $field) {
                                            if (filled($state[$field])) {
                                                return true;
                                            }
                                        }

                                        return false;
                                    }
                                )
                                ->schema([
                                    TextInput::make('line1')
                                        ->columnSpan(3)
                                        ->label('Address Line 1'),
                                    TextInput::make('line2')
                                        ->columnSpan(3)
                                        ->label('Address Line 2'),
                                    TextInput::make('suburb')
                                        ->columnSpan(2),
                                    TextInput::make('postcode')
                                        ->columnSpan(2)
                                        ->length(4),
                                    Select::make('state')
                                        ->columnSpan(2)
                                        ->options(Address::STATES)
                                ])
                        ])->columnSpanFull(),
                    Section::make()
                        ->schema([
                            TextInput::make('id')
                                ->disabled()
                                ->readOnly(),
                            Select::make('type')
                                ->live()
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
     * @param bool $dobRequired
     *
     * @return array
     */
    private static function memberForm(bool $dobRequired = false): array
    {
        $schema = [];

        $schema[] = TextInput::make('name')
            ->columnSpan(3)
            ->required();
        
        if ($dobRequired) {
            $schema[] = DatePicker::make('date_of_birth')
                ->columnSpan(1)
                ->required();
        } else {
            $schema[] = DatePicker::make('date_of_birth')
                ->columnSpan(1);
        }

        $schema[] = TextInput::make('email')
            ->columnSpan(2)
            ->email();

        $schema[] = TextInput::make('phone')
            ->columnSpan(2);

        return [
            Grid::make()
                ->columns(4)
                ->schema($schema)
        ];
    }
}
