<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Models\Team;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\Model;

class ListAccounts extends ListRecords
{
    protected static string $resource = AccountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                
        ];
    }
}
