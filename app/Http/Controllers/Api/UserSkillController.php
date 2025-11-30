<?php

namespace App\Http\Controllers\Api;

use App\Data\AddUserSkillData;
use App\Data\SkillData;
use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;

use function Laravel\Prompts\info;

class UserSkillController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/users/skills",
     *     summary="Ajouter des compétences à l'utilisateur connecté",
     *     description="Attache une ou plusieurs compétences à l'utilisateur actuellement connecté",
     *     tags={"UserSkills"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"skills"},
     *             @OA\Property(property="skills", type="array", @OA\Items(type="string"), example={"PHP","Laravel"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Compétences ajoutées avec succès"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(AddUserSkillData $data)
    {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        foreach ($data->skills as $skill) {
            $newSkill = Skill::query()->where("name", $skill)->first();
            if (!$newSkill) {
                $newSkill = Skill::query()->create([
                    "name" => $skill
                ]);
            }
            $user->skills()->attach([$newSkill->id]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/skills/{id}/users",
     *     summary="Supprimer une compétence de l'utilisateur connecté",
     *     description="Détache une compétence de l'utilisateur actuellement connecté",
     *     tags={"UserSkills"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la compétence à détacher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Compétence détachée avec succès"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Compétence non trouvée")
     * )
     */
    public function destoy(int $id)
    {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        $skill = Skill::findOrFail($id);
        $user->skills()->detach([$skill->id]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{userId}/skills",
     *     summary="Lister les compétences d'un utilisateur",
     *     description="Récupère la liste des compétences associées à un utilisateur donné",
     *     tags={"UserSkills"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des compétences",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="name", type="string", example="PHP"),
     *                 @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Utilisateur non trouvé")
     * )
     */
    public function getUserSkills(int $userId)
    {

        $skills = Skill::whereHas("users", function ($query) use ($userId) {
            $query->where("id", $userId);
        })->paginate(20);

        return SkillData::collect($skills);
    }
}
