<?php

namespace App\Data;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Optional;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class MissionData extends Data
{
    public function __construct(
        public Optional|int $id,
        #[Required, StringType, Max(255)]
        public string $title,
        #[Required, IntegerType]
        public int $category_id,
        #[Required, StringType, Max(1000)]
        public string $description,
        #[Required, IntegerType]
        public int $user_id,
        #[Required, IntegerType]
        public int $duration_days,
        #[Required, Numeric]
        public float $budget,
        #[Required, StringType]
        public string $status,
        public Optional|string $created_at,
        public Optional|string $updated_at,
        #[AutoWhenLoadedLazy]
        public Optional|CategoryData $category,
        #[AutoWhenLoadedLazy]
        public Optional|UserData $owner,
        /** @var Lazy|Collection<int, ParticipationData> */
        #[AutoWhenLoadedLazy]
        public Lazy|Collection $participations
    ) {}
}
