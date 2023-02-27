<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Models\Team;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Wizard\Step;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateAccount extends CreateRecord
{

    // use HasWizard;

    protected static string $resource = AccountResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Account')
                ->description('Account information and contact details')
                ->schema([
                    Card::make(AccountResource::getFormSchema())->columns()
                ]),
            Step::make('users')
                ->description('Add users to this account')
                ->schema([
                    Card::make(AccountResource::getFormSchema('users'))->columns(2)
                ])
        ];
    }
}
