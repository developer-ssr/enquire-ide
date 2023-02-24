<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\UsersRelationManager;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'manage';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema(static::getFormSchema())
                    ->columns(2)
            ]);
    }

    public static function getFormSchema(?string $section = null): array
    {
        if ($section === 'users') {
            return [
                Repeater::make('users')
                    ->relationship()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                    ])
            ];
        }
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->minLength(5),
            TextInput::make('email')
                ->email()
                ->required(),
            TextInput::make('tel_no')
                ->label('Contact Number')
                ->required()
                ->tel(),
            TextInput::make('meta.address')
                ->label('Address')
                ->required()
                ->maxLength(255)
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable('name')
                    ->sortable()
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
