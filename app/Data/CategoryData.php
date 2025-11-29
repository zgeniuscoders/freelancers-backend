<?php

namespace App\Data;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Optional;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class CategoryData extends Data
{
    public function __construct(
        public Optional|int $id,
        public string $name,
        public Optional|string $description,
        public Optional|string $created_at,
        public Optional|string $updated_at,
        /** @var Lazy|Collection<int, MissionData>  */
        #[AutoWhenLoadedLazy]
        public Optional|Collection $missions
    ) {}
}
