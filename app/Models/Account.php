<?php

namespace App\Models;

use App\Data\Account as DataAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'meta' => 'array'
    ];


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($account) {
            $account->users()->delete();
        });
    }
}
