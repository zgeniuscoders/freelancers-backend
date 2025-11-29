<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class ParticipationData extends Data
{
    public function __construct(
        public ?int $id,
        public string $message,
        public Optional|string $status,
        public Optional|CarbonImmutable $created_at,
        public Optional|CarbonImmutable $updated_at,
    ) {}
}
