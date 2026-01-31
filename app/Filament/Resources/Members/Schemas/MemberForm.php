<?php

namespace App\Filament\Resources\Members\Schemas;

use App\Models\Address;
use App\Models\Identification;
use App\Models\Member;
use App\Models\MemberMembership;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MemberForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Grid::make()
                        ->schema([
                            Section::make([
                                TextInput::make('name')
                                    ->unique()
                                    ->required(),
                                DatePicker::make('date_of_birth')
                                    ->required(fn (?Member $record):bool => $record ? $record->memberships()->wherePivot('type', 'member')->count() : false),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email(),
                                TextInput::make('phone')
                                    ->tel(),
                            ])->columnSpanFull(),
                            Fieldset::make('Address')
                                ->relationship(
                                    condition: fn (?array $state):bool => filled($state['line1']),
                                    name: 'address'
                                )
                                ->schema([
                                    TextInput::make('line1')
                                        ->columnSpanFull()
                                        ->live()
                                        ->required(fn (Get $get): bool => self::addressFieldsRequired($get)),
                                    TextInput::make('line2')
                                        ->columnSpanFull()
                                        ->live(),
                                    TextInput::make('suburb')
                                        ->live()
                                        ->required(fn (Get $get): bool => self::addressFieldsRequired($get)),
                                    TextInput::make('postcode')
                                        ->length(4)
                                        ->live()
                                        ->required(fn (Get $get): bool => self::addressFieldsRequired($get)),
                                    Select::make('state')
                                        ->live()
                                        ->options(Address::STATES)
                                        ->required(fn (Get $get): bool => self::addressFieldsRequired($get)),
                                ])
                                ->columns(3)
                                ->columnSpanFull(),
                            Fieldset::make('Identifications')
                                ->schema([
                                    Repeater::make('identifications')
                                        ->columns(3)
                                        ->columnSpanFull()
                                        ->hiddenLabel()
                                        ->defaultItems(0)
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
                                        ])
                                ])->columnSpanFull()
                        ]),
                    Section::make([
                        TextInput::make('id')
                            ->disabled()
                            ->label('Member ID')
                            ->readOnly(),
                        DatePicker::make('created_at')
                            ->disabled()
                            ->readonly()
                            ->displayFormat('d/m/Y H:ia')
                            ->native(false),
                        DatePicker::make('updated_at')
                            ->disabled()
                            ->readOnly()
                            ->displayFormat('d/m/Y H:ia')
                            ->native(false),
                    ])->grow(false)
                ])->from('md')->columnSpanFull()
            ]);
    }

    /**
     * @return bool
     */
    protected static function addressFieldsRequired(Get $get): bool
    {
        foreach (['line1', 'line2', 'suburb', 'postcode', 'state'] as $field) {
            if ($get($field)) {
                return true;
            }
        }

        return false;
    }
}
