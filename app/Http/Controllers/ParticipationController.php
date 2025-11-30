<?php

namespace App\Http\Controllers;

use App\Data\AddParticipationData;
use App\Data\ParticipationData;
use App\Models\Participation;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/participations",
     *     summary="Ajouter une participation",
     *     description="Crée une nouvelle participation à une mission",
     *     tags={"Participations"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message","user_id","mission_id"},
     *             @OA\Property(property="message", type="string", example="Je souhaite participer à cette mission."),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="mission_id", type="integer", example=5),
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Participation créée",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="message", type="string", example="Je souhaite participer à cette mission."),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(AddParticipationData $data)
    {
        $participation = Participation::query()
            ->create($data->toArray());
        $participation->load(["mission", "participant"]);

        return ParticipationData::from($participation);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/participations/{id}",
     *     summary="Mettre à jour une participation",
     *     description="Met à jour les détails d'une participation existante",
     *     tags={"Participations"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la participation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message","user_id","mission_id"},
     *             @OA\Property(property="message", type="string", example="Je souhaite participer à cette mission."),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="mission_id", type="integer", example=5),
     *             @OA\Property(property="status", type="string", example="approved")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Participation mise à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="message", type="string", example="Je souhaite participer à cette mission."),
     *             @OA\Property(property="status", type="string", example="approved"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T12:30:00Z")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Participation non trouvée"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(AddParticipationData $data, int $id)
    {
        $participation = Participation::query()->findOrFail($id);
        $participation->update($data->toArray());
        return ParticipationData::from($participation);
    }
}
