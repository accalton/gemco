<?php

namespace App\Filament\Resources\Memberships\Schemas;

use App\Models\Address;
use App\Models\MemberMembership;
use App\Models\Membership;
use DateTime;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                                ->createOptionForm(self::memberForm())
                                ->editOptionForm(self::memberForm())
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
                                )
                                ->extraItemActions([
                                    Action::make('View Member')
                                        ->color('warning')
                                        ->icon('heroicon-m-eye')
                                        ->url(function (array $arguments, Repeater $component) {
                                            $itemState = $component->getItemState($arguments['item']);

                                            if ($id = ($itemState['member_id'] ?? null)) {
                                                return route('filament.admin.resources.members.edit', ['record' => $id]);
                                            }
                                        })
                                ])
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
                                        ->createOptionForm(self::contactForm())
                                        ->editOptionForm(self::contactForm())
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
                                    TextInput::make('relationship')
                                        ->required(),
                                ])
                                ->orderColumn('order'),
                            self::addressForm(),
                        ])->columnSpanFull(),
                    Section::make()
                        ->schema(self::sidebarForm())->grow(false)
                ])->from('md')->columnSpanFull()
            ]);
    }

    private static function addressForm(): Fieldset
    {
        return Fieldset::make('Address')
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
                    ->columnSpan(2)
                    ->required(),
                TextInput::make('postcode')
                    ->columnSpan(2)
                    ->length(4)
                    ->required(),
                Select::make('state')
                    ->columnSpan(2)
                    ->options(Address::STATES)
            ]);
    }

    private static function contactForm(): array
    {
        return [
            Grid::make()
                ->columns(4)
                ->schema([
                    TextInput::make('name')
                        ->columnSpan(3)
                        ->required(),
                    DatePicker::make('date_of_birth')
                        ->columnSpan(1),
                    TextInput::make('email')
                        ->columnSpan(2)
                        ->email(),
                    TextInput::make('phone')
                        ->columnSpan(2)
                        ->required()
                        ->tel()
                ])
        ];
    }

    private static function hideGuardianForm(Get $get): bool
    {
        if ($dateOfBirth = DateTime::createFromFormat('Y-m-d', $get('date_of_birth'))) {
            $currentDate = new DateTime();

            $diff = $currentDate->diff($dateOfBirth);

            return $diff->y >= 18;
        }

        return true;
    }

    private static function memberForm(): array
    {
        return [
            Grid::make()
                ->columns(4)
                ->schema([
                    TextInput::make('name')
                        ->columnSpan(3)
                        ->required(),
                    DatePicker::make('date_of_birth')
                        ->columnSpan(1)
                        ->live()
                        ->required(),
                    TextInput::make('email')
                        ->columnSpan(2)
                        ->email()
                        ->required(),
                    TextInput::make('phone')
                        ->columnSpan(2)
                        ->required()
                        ->tel(),
                    Fieldset::make()
                        ->columns(4)
                        ->columnSpanFull()
                        ->hidden(fn (Get $get): bool => self::hideGuardianForm($get))
                        ->label('Parent/Guardian')
                        ->relationship('guardian')
                        ->schema([
                            TextInput::make('name')
                                ->columnSpan(3)
                                ->required(),
                            DatePicker::make('date_of_birth')
                                ->columnSpan(1)
                                ->required(),
                            TextInput::make('email')
                                ->columnSpan(2)
                                ->email()
                                ->required(),
                            TextInput::make('phone')
                                ->columnSpan(2)
                                ->required()
                                ->tel(),
                        ])
                ])
        ];
    }

    private static function sidebarForm(): array
    {
        return [
            TextInput::make('id')
                ->disabled()
                ->readOnly(),
            Select::make('type')
                ->live()
                ->options(Membership::TYPES)
                ->required(),
            Select::make('status')
                ->live()
                ->options(Membership::STATUSES)
                ->required(),
            Textarea::make('cancellation_reason')
                ->helperText(fn (Field $component, ?string $state): string =>
                    'Characters left: ' . ($component->getMaxLength() - strlen($state))
                )
                ->hidden(fn (Get $get): bool => $get('status') != 'cancelled')
                ->live()
                ->maxLength(500),
            DatePicker::make('expiry')
                ->required()
        ];
    }
}
