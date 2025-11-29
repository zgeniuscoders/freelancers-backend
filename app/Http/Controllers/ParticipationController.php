<?php

namespace App\Http\Controllers;

use App\Data\AddParticipationData;
use App\Data\ParticipationData;
use App\Models\Participation;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    public function store(AddParticipationData $data)
    {
        $participation = Participation::query()
            ->create($data->toArray());
        $participation->load(["mission", "participant"]);
        
        return ParticipationData::from($participation);
    }

    public function update(AddParticipationData $data, int $id)
    {
        $participation = Participation::query()->findOrFail($id);
        $participation->update($data->toArray());
        return ParticipationData::from($participation);
    }
}
