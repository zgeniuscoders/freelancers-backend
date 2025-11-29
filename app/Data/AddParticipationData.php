<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class AddParticipationData extends Data
{
    public function __construct(
        public ?int $id,
        public string $message,
        #[Exists("users", "id")]
        public int $user_id,
        #[Exists("missions", "id")]
        public int $mission_id,
        public Optional|string $status,
    ) {}
}
