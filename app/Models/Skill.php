<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ["name"];

    public function missions()
    {
        return $this->belongsToMany(Mission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
