<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/v1/user-profiles",
     *     summary="Créer un profil utilisateur",
     *     description="Ajoute un nouveau profil utilisateur",
     *     tags={"UserProfiles"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"bio","user_id"},
     *                 @OA\Property(property="bio", type="string", maxLength=500, example="Je suis développeur full-stack."),
     *                 @OA\Property(property="avatar", type="string", format="binary"),
     *                 @OA\Property(property="user_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Profil créé",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="bio", type="string", example="Je suis développeur full-stack."),
     *             @OA\Property(property="avatar", type="string", example="http://example.com/storage/avatars/avatar.jpg"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function store(AddUserProfileRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            $userProfile = UserProfile::query()->create($data);

            return new UserProfileResource($userProfile);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user-profiles/{id}",
     *     summary="Afficher un profil utilisateur",
     *     description="Récupère les détails d'un profil utilisateur par son ID",
     *     tags={"UserProfiles"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du profil",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du profil",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="bio", type="string", example="Je suis développeur full-stack."),
     *             @OA\Property(property="avatar", type="string", example="http://example.com/storage/avatars/avatar.jpg"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Profil non trouvé"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function show(string $id)
    {
        try {
            $userProfile = UserProfile::with('user')->find($id);

            if (!$userProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil utilisateur non trouvé.'
                ], 404);
            }

            return new UserProfileResource($userProfile);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user-profiles/{id}",
     *     summary="Mettre à jour un profil utilisateur",
     *     description="Modifie un profil existant",
     *     tags={"UserProfiles"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du profil",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="bio", type="string", maxLength=500, example="Je suis développeur full-stack."),
     *                 @OA\Property(property="avatar", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil mis à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="bio", type="string", example="Je suis développeur full-stack."),
     *             @OA\Property(property="avatar", type="string", example="http://example.com/storage/avatars/avatar.jpg"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Profil non trouvé"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function update(UpdateUserProfileRequest $request, string $id)
    {
        try {
            $userProfile = UserProfile::find($id);

            if (!$userProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil utilisateur non trouvé.'
                ], 404);
            }

            $data = $request->validated();

            if ($request->hasFile('avatar')) {
                if ($userProfile->avatar && Storage::disk('public')->exists($userProfile->avatar)) {
                    Storage::disk('public')->delete($userProfile->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            $userProfile->update($data);

            return new UserProfileResource($userProfile);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user-profiles/{id}",
     *     summary="Supprimer un profil utilisateur",
     *     description="Supprime un profil existant",
     *     tags={"UserProfiles"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du profil",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Profil supprimé avec succès"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Profil non trouvé"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $userProfile = UserProfile::find($id);

            if (!$userProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil utilisateur non trouvé.'
                ], 404);
            }

            if ($userProfile->avatar && Storage::disk('public')->exists($userProfile->avatar)) {
                Storage::disk('public')->delete($userProfile->avatar);
            }

            $userProfile->delete();

            return response()->json([
                'success' => true,
                'message' => 'Profil utilisateur supprimé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
