<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Identification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Tabs::make()
                        ->columnSpanFull()
                        ->tabs([
                            Tab::make('User Details')
                                ->schema(
                                    self::detailsForm(),
                                ),
                            Tab::make('Identifications')
                                ->schema(
                                    self::identificationForm()
                                )
                        ]),
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

    public static function detailsForm(): array
    {
        return [
            TextInput::make('name')
                ->required(),
            DatePicker::make('date_of_birth')
                ->required(),
            TextInput::make('contact_email')
                ->email()
                ->required(),
            TextInput::make('phone')
                ->required()
                ->tel(),
            Select::make('groups')
                ->multiple()
                ->preload()
                ->relationship(name: 'groups', titleAttribute: 'title')
        ];
    }

    public static function identificationForm(): array
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
}
