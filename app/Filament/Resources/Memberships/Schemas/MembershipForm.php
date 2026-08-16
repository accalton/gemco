<?php

namespace App\Filament\Resources\Memberships\Schemas;

use App\Filament\Resources\Users\Schemas\UserForm;
use App\Models\Membership;
use App\Models\MembershipUser;
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
                            Select::make('type')
                                ->columnSpanFull()
                                ->live()
                                ->options(Membership::TYPES)
                                ->required(),
                            Tabs::make('Tabs')
                                ->columnSpanFull()
                                ->tabs([
                                    Tab::make('Member Details')
                                        ->schema([
                                            Repeater::make('members')
                                                ->label(fn (array $state): string => count($state) > 1 ? 'Members' : 'Member')
                                                ->maxItems(fn (Get $get): ?int => $get('type') != 'family' ? 1 : 5)
                                                ->minItems(1)
                                                ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): array => array_merge(
                                                    $data,
                                                    ['type' => MembershipUser::TYPE_MEMBER]
                                                ))
                                                ->relationship(
                                                    modifyQueryUsing: fn (Builder $query): Builder => $query->where('type', 'member'),
                                                    name: 'membership_user'
                                                )
                                                ->simple(
                                                    Select::make('user_id')
                                                        ->createOptionForm(
                                                            [UserForm::userForm()]
                                                        )
                                                        ->editOptionForm(
                                                            [UserForm::userForm()]
                                                        )
                                                        ->preload()
                                                        ->relationship(
                                                            modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query
                                                                ->where(function (Builder $query) use ($get) {
                                                                    $query->where(function (Builder $query) use ($get) {
                                                                        $query->whereDoesntHave('membership_user', function (Builder $query) {
                                                                            $query->where('membership_user.type', 'member');
                                                                        })->whereNotIn('id', self::currentUserIds($get));
                                                                    })->orWhere(function (Builder $query) use ($get) {
                                                                        $query->whereRelation('memberships', 'memberships.id', $get('../../id'))
                                                                            ->whereNotIn('id', self::currentUserIds($get));
                                                                    });
                                                                }),
                                                            name: 'user',
                                                            titleAttribute: 'name'
                                                        )
                                                        ->required()
                                                        ->searchable()
                                                )
                                        ]),
                                    Tab::make('Contact Details')
                                        ->schema([
                                            Repeater::make('contacts')
                                                ->defaultItems(0)
                                                ->minItems(fn (Get $get): ?int => $get('type') === 'youth' ? 1 : null)
                                                ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): array => array_merge(
                                                    $data,
                                                    ['type' => MembershipUser::TYPE_CONTACT]
                                                ))
                                                ->relationship(
                                                    modifyQueryUsing: fn (Builder $query): Builder => $query->where('type', 'contact'),
                                                    name: 'membership_user'
                                                )
                                                ->simple(
                                                    Select::make('user_id')
                                                        ->createOptionForm(
                                                            [UserForm::userForm()]
                                                        )
                                                        ->editOptionForm(
                                                            [UserForm::userForm()]
                                                        )
                                                        ->preload()
                                                        ->relationship(
                                                            modifyQueryUsing: fn (Builder $query, Get $get): Builder => $query->where(
                                                                function (Builder $query) use ($get) {
                                                                    $query->whereNotIn('id', self::currentUserIds($get));
                                                                }
                                                            ),
                                                            name: 'user',
                                                            titleAttribute: 'name'
                                                        )
                                                        ->required()
                                                        ->searchable()
                                                )
                                        ]),
                                ]),
                        ]),
                    Section::make()
                        ->schema(self::sidebarForm())
                        ->grow(false),
                ])->from('md')->columnSpanFull(),
            ]);
    }

    private static function currentUserIds(Get $get): array
    {
        $userIds = [];
        foreach ($get('../../members') as $member) {
            $userIds[] = $member['user_id'];
        }

        return array_filter(array_diff($userIds, [$get('user_id')]));
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
