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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
