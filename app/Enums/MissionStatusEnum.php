<?php 

namespace App\Enums;


enum MissionStatusEnum:string{
    case OPEN = "open";
    case DONE = "done";
    case COMPLETED = "completed";
    case CANCELED = "canceled";
    case PROGRESS = "progress";
    case DRAFT = "draft";
}