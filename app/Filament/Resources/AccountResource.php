<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Filament\Resources\TeamResource\RelationManagers\TeamsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\UsersRelationManager;
use App\Models\Account;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'manage';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Card::make()
                            ->schema(static::getFormSchema())
                            ->columns(2),
                        // Forms\Components\Section::make('Account users')
                        //     ->schema(static::getFormSchema('users'))
                    ])
                    ->columnSpan(['lg' => fn (?Account $record) => $record === null ? 3 : 2])
                ,
                Card::make()
                    ->schema([
                        Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Account $record): ?string => $record->created_at?->diffForHumans()),
                        Placeholder::make('updated_at')
                            ->label('Last modified')
                            ->content(fn (Account $record): ?string => $record->updated_at?->diffForHumans()),
                        Placeholder::make('license')
                            ->label('License Valid Until')
                            ->content(fn (Account $record): ?string => Carbon::make($record->meta['expires_at'])?->format('F j, Y'))
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Account $record) => $record === null)
            ])
            ->columns(3);
    }

    public static function getFormSchema(?string $section = null): array
    {
        if ($section === 'users') {
            return [
                // Repeater::make('users')
                //     ->relationship()
                //     ->schema([
                        TextInput::make('name')
                            ->default('Kriss')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->default('iamcrisjohn@gmail.com')
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->default('CodeX8910')
                            ->required()
                            ->same('passwordConfirmation')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        TextInput::make('passwordConfirmation')
                            ->label('Password Confirmation')
                            ->password()
                            ->required()
                            ->default('CodeX8910')
                            ->dehydrated(false),
                        Select::make('role')
                            ->label('Account Role')
                            ->options([
                                // 'super' => 'Super Administrator',
                                'admin' => 'Administrator',
                                'user' => 'Standard User'
                            ])
                    // ])
            ];
        }
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->default('SSR')
                ->minLength(3),
            TextInput::make('email')
                ->email()
                ->default('iamcrisjohn@gmail.com')
                ->required(),
            TextInput::make('tel_no')
                ->label('Contact Number')
                ->default('09173035989')
                ->required()
                ->tel(),
            TextInput::make('meta.address')
                ->label('Address')
                ->default('WR5 3FR')
                ->required()
                ->maxLength(255),
            DatePicker::make('meta.expires_at')
                ->label('License valid until')
                ->required()
                ->minDate(now())
                ->default(now()->addYear())
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable('name')
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Contact Email')
                    ->sortable(),
                TextColumn::make('tel_no')
                    ->label('Contact No'),
                TextColumn::make('meta.expires_at')
                    ->date()
                    ->label('Validity')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
            TeamsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
