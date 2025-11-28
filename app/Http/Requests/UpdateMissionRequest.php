<?php

namespace App\Http\Requests;

use App\Enums\MissionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMissionRequest extends FormRequest
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
            "title" => ["sometimes","string","min:3"],
            "description"=> ["sometimes", "string"],
            "duration_days" => ["sometimes","integer"],
            "status" => ["sometimes",new Enum(MissionStatusEnum::cases())],
            "category_id" => ["sometimes","exists:categories,id"],
            "budget" => ["sometimes","integer"]
        ];
    }
}
