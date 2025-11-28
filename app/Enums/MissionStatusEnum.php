<?php 

namespace App\Enums;


enum MissionStatusEnum:string{
    case DONE = "done";
    case COMPLETED = "completed";
    case CANCELED = "canceled";
    case PROGRESS = "progress";
    case DRAFT = "draft";
}