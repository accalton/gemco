<?php

namespace App\Filament\Resources\Memberships\Schemas;

use App\Models\MemberMembership;
use App\Models\Membership;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
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
                            Repeater::make('members')
                                ->label('Members')
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
                                                ->whereRelation('member_memberships', 'id', $record->id ?? null)
                                                ->orWhereDoesntHave('member_memberships'),
                                            name: 'member',
                                            titleAttribute: 'name'
                                        )
                                        ->searchable(),
                                ),
                            Repeater::make('contacts')
                                ->label('Contacts')
                                ->mutateRelationshipDataBeforeCreateUsing(fn (array $data) => array_merge($data, ['type' => MemberMembership::TYPE_CONTACT]))
                                ->relationship(
                                    modifyQueryUsing: fn (Builder $query): Builder => $query->where('type', 'contact'),
                                    name: 'member_memberships'
                                )
                                ->simple(
                                    Select::make('member_id')
                                        ->createOptionForm(self::memberForm())
                                        ->editOptionForm(self::memberForm())
                                        ->preload()
                                        ->relationship(
                                            modifyQueryUsing: fn (Builder $query, ?MemberMembership $record): Builder => $query
                                                ->whereRelation('member_memberships', 'id', $record->id ?? null)
                                                ->orWhereDoesntHave('member_memberships'),
                                            name: 'member',
                                            titleAttribute: 'name'
                                        )
                                        ->searchable(),
                                )
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
