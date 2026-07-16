<?php

namespace App\Filament\Resources\Memberships\Schemas;

use App\Models\Address;
use App\Models\Identification;
use App\Models\Membership;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MembershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Tabs::make('Tabs')
                        ->tabs([
                            Tab::make('Membership Details')
                                ->schema([
                                    Select::make('type')
                                        ->live()
                                        ->options(Membership::TYPES)
                                        ->required(),
                                    self::addressForm()
                                ]),
                            Tab::make('Member Details')
                                ->schema([
                                    self::memberForm(),
                                ]),
                            Tab::make('Contact Details')
                                ->schema([
                                    self::contactForm(),
                                ])
                        ]),
                    Section::make()
                        ->schema(self::sidebarForm())->grow(false)
                ])->from('md')->columnSpanFull(),
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

    private static function contactForm(): Repeater
    {
        return Repeater::make('contacts')
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
            ->minItems(fn (Get $get): ?int => $get('type') === 'youth' ? 1 : null)
            ->relationship(name: 'contacts')
            ->schema(self::userForm('contacts'));
    }

    private static function identificationForm()
    {
        return Fieldset::make('Identifications')
            ->schema([
                Repeater::make('identifications')
                    ->collapsible()
                    ->columns(3)
                    ->columnSpanFull()
                    ->defaultItems(0)
                    ->hiddenLabel()
                    ->itemLabel(fn (array $state): ?string => Identification::TYPES[$state['type']] ?? null)
                    ->relationship(name: 'identifications')
                    ->schema([
                        Select::make('type')
                            ->columnSpan(2)
                            ->options(Identification::TYPES)
                            ->required(),
                        DatePicker::make('expiry')
                            ->required(),
                        TextInput::make('number')
                            ->columnSpanFull()
                            ->required(),
                        Textarea::make('details')
                            ->columnSpanFull()
                            ->helperText(fn (Field $component, ?string $state): string =>
                                'Characters left: ' . ($component->getMaxLength() - strlen($state))
                            )
                            ->maxLength(2000)
                            ->live()
                    ])
            ])->columnSpanFull();
    }

    private static function memberForm(): Repeater
    {
        return Repeater::make('members')
            ->collapsible()
            ->defaultItems(1)
            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
            ->maxItems(fn (Get $get): ?int => $get('type') !== 'family' ? 1 : null)
            ->minItems(1)
            ->relationship(name: 'members')
            ->schema(self::userForm('members'));
    }

    private static function userForm(string $type): array
    {
        return [
            Grid::make()
                ->columns(4)
                ->schema([
                    Hidden::make('membership_type')
                        ->default($type)
                        ->dehydrateStateUsing(fn (): string => $type),
                    TextInput::make('name')
                        ->columnSpan(3)
                        ->required(),
                    DatePicker::make('date_of_birth')
                        ->columnSpan(1)
                        ->live()
                        ->required(),
                    TextInput::make('contact_email')
                        ->columnSpan(2)
                        ->email()
                        ->required(),
                    TextInput::make('phone')
                        ->columnSpan(2)
                        ->required()
                        ->tel(),
                    self::identificationForm(),
                ])
        ];
    }

    private static function sidebarForm(): array
    {
        return [
            TextInput::make('id')
                ->disabled()
                ->readOnly(),
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
