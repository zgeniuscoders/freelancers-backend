<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class AddMissionData extends Data
{
    public function __construct(
        #[Required, StringType, Min(3)]
        public string $title,
        #[Required, StringType, Max(500)]
        public string $description,
        #[Required, IntegerType]
        public int $duration_days,
        #[Required, Exists("categories", "id")]
        public int $category_id,
        #[Required, IntegerType]
        public int $budget,
        #[ArrayType]
        public array $skills,
    ) {}
}
