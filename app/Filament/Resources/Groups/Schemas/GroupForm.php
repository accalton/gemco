<?php

namespace App\Filament\Resources\Groups\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class GroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make('')
                        ->schema([
                            TextInput::make('title')
                                ->columnSpanFull()
                                ->maxLength(255)
                                ->required(),
                            Repeater::make('users')
                                ->addActionLabel('Add User')
                                ->label('Users')
                                ->relationship(name: 'group_user')
                                ->simple(
                                    Select::make('user_id')
                                        ->preload()
                                        ->relationship(
                                            modifyQueryUsing: function (Builder $query, Get $get) {
                                                return $query->whereRelation('group_user', 'group_user.id', $get('id'))
                                                    ->orWhereNotIn('id', self::currentUserIds($get));
                                            },
                                            name: 'user',
                                            titleAttribute: 'name'
                                        )
                                        ->required()
                                        ->searchable()
                                )
                        ]),
                    Section::make()
                        ->schema([
                            TextInput::make('id')
                                ->disabled()
                                ->label('ID')
                                ->readOnly(),
                            DatePicker::make('created_at')
                                ->disabled()
                                ->readOnly(),
                            DatePicker::make('updated_at')
                                ->disabled()
                                ->readOnly()
                        ])
                        ->grow(false),
                ])->from('md')->columnSpanFull(),
            ]);
    }

    private static function currentUserIds(Get $get): array
    {
        $userIds = [];
        foreach ($get('../../users') as $userGroup) {
            $userIds[] = $userGroup['user_id'];
        }

        return array_filter(array_diff($userIds, [$get('user_id')]));
    }
}
