<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;

class Account extends Data
{

    public function __construct(
        public string $address,
        #[WithCast(DateTimeInterfaceCast::class, type: Carbon::class)]
        public $expires_at
    )
    {
    }
}