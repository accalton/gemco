<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Address;
use App\Models\Identification;
use DateTime;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    self::userForm(),
                    Section::make()
                        ->grow(false)
                        ->schema([
                            TextInput::make('id')
                                ->disabled()
                                ->label('User ID')
                                ->readOnly(),
                            DatePicker::make('created_at')
                                ->disabled()
                                ->readOnly(),
                            DatePicker::make('updated_at')
                                ->disabled()
                                ->readOnly(),
                        ]),
                ])->from('md')->columnSpanFull()
            ]);
    }

    public static function userForm(): Tabs
    {
        return Tabs::make()
            ->columnSpanFull()
            ->tabs([
                Tab::make('User Details')
                    ->schema(
                        self::detailsForm(),
                    ),
                Tab::make('Address')
                    ->schema(
                        self::addressFOrm()
                    ),
                Tab::make('Identifications')
                    ->schema(
                        self::identificationForm()
                    )
            ]);
    }

    private static function addressForm(): array
    {
        return [
            Fieldset::make()
                ->columns(3)
                ->contained(false)
                ->relationship(name: 'address')
                ->schema([
                    TextInput::make('line1')
                        ->columnSpanFull()
                        ->label('Address Line 1')
                        ->maxLength(255),
                    TextInput::make('line2')
                        ->columnSpanFull()
                        ->label('Address Line 2')
                        ->maxLength(255),
                    TextInput::make('suburb')
                        ->columnSpan(1)
                        ->required()
                        ->maxLength(255),
                    TextInput::make('postcode')
                        ->columnSpan(1)
                        ->length(4)
                        ->required(),
                    Select::make('state')
                        ->columnSpan(1)
                        ->options(Address::STATES)
                ])
        ];
    }

    private static function detailsForm(): array
    {
        return [
            TextInput::make('name')
                ->maxLength(255)
                ->required(),
            DatePicker::make('date_of_birth')
                ->live()
                ->required(),
            Repeater::make('Guardians')
                ->defaultItems(1)
                ->hidden(fn (Get $get): bool => !self::isMinor($get))
                ->label(fn (array $state): string => count($state) > 1 ? 'Guardians' : 'Guardian')
                ->minItems(1)
                ->relationship('minor_guardian')
                ->simple(
                    Select::make('guardian_id')
                        ->preload()
                        ->relationship(name: 'guardian', titleAttribute: 'name')
                        ->required()
                        ->searchable()
                ),
            TextInput::make('contact_email')
                ->email()
                ->hidden(fn (Get $get): bool => self::isMinor($get))
                ->maxLength(255)
                ->required(),
            TextInput::make('phone')
                ->hidden(fn (Get $get): bool => self::isMinor($get))
                ->maxLength(20)
                ->required()
                ->tel(),
            Select::make('groups')
                ->multiple()
                ->preload()
                ->relationship(name: 'groups', titleAttribute: 'title')
        ];
    }

    private static function identificationForm(): array
    {
        return [
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
                        ->maxLength(255)
                        ->required(),
                    Textarea::make('details')
                        ->columnSpanFull()
                        ->helperText(fn (Field $component, ?string $state): string =>
                            'Characters left: ' . ($component->getMaxLength() - strlen($state))
                        )
                        ->maxLength(2000)
                        ->live()
                ])
        ];
    }

    private static function isMinor(Get $get): bool
    {
        if ($dateOfBirth = DateTime::createFromFormat('Y-m-d', $get('date_of_birth'))) {
            $today = new DateTime();
            $dateOfBirth->modify('+18 years');

            return $dateOfBirth >= $today;
        }

        return false;
    }
}
