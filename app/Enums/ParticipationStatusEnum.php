<?php

namespace App\Enums;

enum ParticipationStatusEnum: string
{
    case PENDING = "pending";
    case ACCEPTED = "accepted";
    case REFUSED = "refused";
    case CANCELLED = "cancelled";
    case COMPLETED = "completed";
}
