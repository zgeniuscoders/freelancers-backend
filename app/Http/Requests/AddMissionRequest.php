<?php

namespace App\Http\Requests;

use App\Enums\MissionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AddMissionRequest extends FormRequest
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
            "title" => ["required","string","min:3"],
            "description"=> ["required", "string"],
            "duration_days" => ["required","integer"],
            "status" => ["required",new Enum(MissionStatusEnum::class)],
            "category_id" => ["required","exists:categories,id"],
            "budget" => ["required","integer"]
        ];
    }
}
