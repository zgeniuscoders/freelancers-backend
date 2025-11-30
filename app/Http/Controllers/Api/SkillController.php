<?php

namespace App\Http\Controllers\Api;

use App\Data\SkillData;
use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/skills",
     *     summary="Lister toutes les compétences",
     *     description="Récupère la liste des compétences",
     *     tags={"Skills"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des compétences",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="PHP"),
     *                 @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $skills = Skill::paginate(20);
        return SkillData::collect($skills);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/skills",
     *     summary="Créer une compétence",
     *     description="Ajoute une nouvelle compétence",
     *     tags={"Skills"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="PHP")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Compétence créée",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="PHP"),
     *             @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(SkillData $data)
    {
        $skill = Skill::query()->create($data->toArray());
        return SkillData::from($skill);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/skills/{id}",
     *     summary="Afficher une compétence",
     *     description="Récupère les détails d'une compétence par son ID",
     *     tags={"Skills"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la compétence",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la compétence",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="PHP"),
     *             @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Compétence non trouvée")
     * )
     */
    public function show(string $id)
    {
        $skill = Skill::query()->findOrFail($id);
        return SkillData::from($skill);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/skills/{id}",
     *     summary="Mettre à jour une compétence",
     *     description="Modifie une compétence existante",
     *     tags={"Skills"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la compétence",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="PHP")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Compétence mise à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="PHP"),
     *             @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Compétence non trouvée"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(SkillData $data, string $id)
    {
        $skill = Skill::query()->findOrFail($id);
        $skill->update($data->toArray());
        return SkillData::from($skill);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/skills/{id}",
     *     summary="Supprimer une compétence",
     *     description="Supprime une compétence existante",
     *     tags={"Skills"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la compétence",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Compétence supprimée"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Compétence non trouvée")
     * )
     */
    public function destroy(string $id)
    {
        $skill = Skill::query()->findOrFail($id);
    }
}
