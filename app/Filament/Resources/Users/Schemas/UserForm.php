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
                            TextInput::make('password')
                                ->password()
                                ->required(),
                            DatePicker::make('date_of_birth')
                                ->required(),
                            TextInput::make('email')
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
                            Repeater::make('memberships')
                                ->defaultItems(0)
                                ->addable(false)
                                ->deletable(false)
                                ->extraItemActions([
                                    Action::make('view')
                                        ->button()
                                        ->color('warning')
                                        ->icon(Heroicon::ArrowRightEndOnRectangle)
                                        ->iconPosition(IconPosition::After)
                                        ->url(function (array $arguments, array $state) {
                                            $itemData = $state[$arguments['item']];

                                            if ($membershipId = $itemData['membership_id'] ?? false) {
                                                return route('filament.admin.resources.memberships.edit', $membershipId);
                                            }
                                        })
                                ])
                                ->label('Membership Type')
                                ->relationship()
                                ->simple(
                                    TextInput::make('type')
                                        ->disabled()
                                        ->formatStateUsing(fn (?string $state): ?string => Membership::TYPES[$state] ?? null)
                                        ->readOnly(),
                                ),
                        ])->grow(false)
                ])->from('md')->columnSpanFull()
            ]);
    }
}
