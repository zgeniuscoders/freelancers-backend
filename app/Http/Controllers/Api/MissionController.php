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
     * @OA\Get(
     *     path="/api/v1/missions",
     *     summary="Lister toutes les missions",
     *     description="Récupère la liste des missions avec propriétaire et catégorie",
     *     tags={"Missions"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des missions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Créer un site web"),
     *                 @OA\Property(property="description", type="string", example="Un site vitrine pour un client"),
     *                 @OA\Property(property="budget", type="integer", example=300),
     *                 @OA\Property(property="durationDays", type="integer", example=7),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe")
     *                 ),
     *                 @OA\Property(property="category", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Web Development")
     *                 ),
     *                 @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $missions = Mission::with(["owner", "category"])->paginate(20);
        return MissionResource::collection($missions);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/missions",
     *     summary="Créer une mission",
     *     description="Ajoute une nouvelle mission",
     *     tags={"Missions"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","duration_days","category_id","budget"},
     *             @OA\Property(property="title", type="string", example="Créer un site web"),
     *             @OA\Property(property="description", type="string", example="Un site vitrine pour un client"),
     *             @OA\Property(property="duration_days", type="integer", example=7),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="budget", type="integer", example=300),
     *             @OA\Property(property="skills", type="array", @OA\Items(type="string"), example={"PHP","Laravel"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Mission créée",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Créer un site web"),
     *             @OA\Property(property="description", type="string", example="Un site vitrine pour un client"),
     *             @OA\Property(property="budget", type="integer", example=300),
     *             @OA\Property(property="durationDays", type="integer", example=7),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe")
     *             ),
     *             @OA\Property(property="category", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Web Development")
     *             ),
     *             @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Get(
     *     path="/api/v1/missions/{id}",
     *     summary="Afficher une mission",
     *     description="Récupère les détails d'une mission par son ID",
     *     tags={"Missions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la mission",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la mission",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Créer un site web"),
     *             @OA\Property(property="description", type="string", example="Un site vitrine pour un client"),
     *             @OA\Property(property="budget", type="integer", example=300),
     *             @OA\Property(property="durationDays", type="integer", example=7),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe")
     *             ),
     *             @OA\Property(property="category", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Web Development")
     *             ),
     *             @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Mission non trouvée")
     * )
     */
    public function show(string $id)
    {
        $mission = Mission::query()
            ->with(["owner", "category"])
            ->findOrFail($id);
        return new MissionResource($mission);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/missions/{id}",
     *     summary="Mettre à jour une mission",
     *     description="Modifie une mission existante",
     *     tags={"Missions"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la mission",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Créer un site web"),
     *             @OA\Property(property="description", type="string", example="Un site vitrine pour un client"),
     *             @OA\Property(property="duration_days", type="integer", example=7),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="budget", type="integer", example=300),
     *             @OA\Property(property="skills", type="array", @OA\Items(type="string"), example={"PHP","Laravel"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mission mise à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Créer un site web"),
     *             @OA\Property(property="description", type="string", example="Un site vitrine pour un client"),
     *             @OA\Property(property="budget", type="integer", example=300),
     *             @OA\Property(property="durationDays", type="integer", example=7),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe")
     *             ),
     *             @OA\Property(property="category", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Web Development")
     *             ),
     *             @OA\Property(property="createdAt", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Mission non trouvée"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateMissionRequest $request, string $id)
    {
        $mission = Mission::query()
            ->findOrFail($id);
        $mission->update($request->validated());
        return new MissionResource($mission);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/missions/{id}",
     *     summary="Supprimer une mission",
     *     description="Supprime une mission existante",
     *     tags={"Missions"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la mission",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Mission supprimée"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Mission non trouvée")
     * )
     */
    public function destroy(string $id)
    {
        $mission = Mission::query()
            ->findOrFail($id);
        $mission->delete();
    }
}
