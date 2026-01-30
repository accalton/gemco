<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Identification;
use App\Models\Membership;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('email')
                                ->required(),
                            DatePicker::make('date_of_birth')
                                ->required(),
                            TextInput::make('phone')
                                ->required(),
                            Repeater::make('identifications')
                                ->collapsed()
                                ->defaultItems(0)
                                ->itemLabel(fn (array $state): ?string => Identification::TYPES[$state['type']] ?? null)
                                ->relationship()
                                ->schema([
                                    Select::make('type')
                                        ->options(Identification::TYPES),
                                    TextInput::make('number')
                                        ->required(),
                                    DatePicker::make('issued'),
                                    DatePicker::make('expiry')
                                        ->required(),
                                ])->columns(2)->columnSpanFull()
                        ])->columns(2)->columnSpanFull(),
                    Section::make()
                        ->schema([
                            TextInput::make('id')
                                ->disabled()
                                ->readOnly(),
                            DatePicker::make('created_at')
                                ->disabled()
                                ->readOnly(),
                            DatePicker::make('updated_at')
                                ->disabled()
                                ->readOnly(),
                        ])->grow(false)
                ])->from('md')->columnSpanFull()
            ]);
    }
}
