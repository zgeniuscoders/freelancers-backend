<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bio' => 'sometimes|string|max:500',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'sometimes|exists:users,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'bio.string' => 'La biographie doit être une chaîne de caractères.',
            'bio.max' => 'La biographie ne doit pas dépasser 500 caractères.',
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'avatar.max' => 'L\'image ne doit pas dépasser 2MB.',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.'
        ];
    }
}