<?php

namespace App\Data;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Optional;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class UserData extends Data
{
    public function __construct(
        public Optional|int $id,
        public string $name,
        public string $email,
        public Optional|string $created_at,
        public Optional|string $updated_at,
        #[AutoWhenLoadedLazy]
        /** @var Lazy|Collection<int, MissionData> */
        public Lazy|Collection $missions,
        /** @var Lazy|Collection<int, ParticipationData> */
        #[AutoWhenLoadedLazy]
        public Lazy|Collection $participations
    ) {}
}
