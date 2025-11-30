<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class AddUserSkillData extends Data
{
    public function __construct(
        /** @var string[] */
        #[Required, ArrayType]
        public array $skills
    ) {}
}
