<?php

namespace App\Http\Controllers\Api;

use App\Data\AddMissionData;
use App\Enums\MissionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddMissionRequest;
use App\Http\Requests\UpdateMissionRequest;
use App\Http\Resources\MissionResource;
use App\Models\Mission;
use App\Models\Skill;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $missions = Mission::with(["owner", "category"])->paginate(20);
        return MissionResource::collection($missions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddMissionData $data)
    {
        $userId = auth()->user()->id;
        $mission = Mission::query()
            ->create(
                array_merge(
                    $data->toArray(),
                    [
                        "user_id" => $userId,
                        "status" => MissionStatusEnum::OPEN->value
                    ]
                )
            );


        foreach ($data->skills as $skill) {
            $newSkill = Skill::query()->where("name", $skill)->first();
            if (!$newSkill) {
                $newSkill = Skill::query()->create([
                    "name" => $skill
                ]);
            }
            $mission->skills()->attach([$newSkill->id]);
        }

        return new MissionResource($mission);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mission = Mission::query()
            ->with(["owner", "category"])
            ->findOrFail($id);
        return new MissionResource($mission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMissionRequest $request, string $id)
    {
        $mission = Mission::query()
            ->findOrFail($id);
        $mission->update($request->validated());
        return new MissionResource($mission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mission = Mission::query()
            ->findOrFail($id);
        $mission->delete();
    }
}
