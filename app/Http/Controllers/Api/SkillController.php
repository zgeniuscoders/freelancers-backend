<?php

namespace App\Http\Controllers\Api;

use App\Data\SkillData;
use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::paginate(20);
        return SkillData::collect($skills);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkillData $data)
    {
        $skill = Skill::query()->create($data->toArray());
        return SkillData::from($skill);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $skill = Skill::query()->findOrFail($id);
        return SkillData::from($skill);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SkillData $data, string $id)
    {
        $skill = Skill::query()->findOrFail($id);
        $skill->update($data->toArray());
        return SkillData::from($skill);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skill = Skill::query()->findOrFail($id);
    }
}
