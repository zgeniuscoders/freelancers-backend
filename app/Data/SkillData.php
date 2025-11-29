<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class SkillData extends Data
{
    public function __construct(
        #[Min(3)]
        public string $name,
        public Optional|CarbonImmutable $created_at
    ) {}
}
