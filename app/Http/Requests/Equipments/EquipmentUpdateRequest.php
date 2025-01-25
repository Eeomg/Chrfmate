<?php

namespace App\Http\Requests\Equipments;

use App\Rules\BelongsToWorkSpaceRule;
use App\Rules\UniqueEquipmentNameRule;
use App\Http\Requests\AbstractApiRequest;
use App\Models\Equipment;

/**
 * @OA\Schema(
 *     schema="EquipmentUpdateRequest",
 *     type="object",
 *     required={"name", "unit", "quantity", "workspace_id"},
 *     @OA\Property(property="name", type="string", example="dish 2"),
 *     @OA\Property(property="cover", type="binary", example="image.jpg"),
 *     @OA\Property(property="description", type="string", example="some decription"),
 *     @OA\Property(property="quantity", type="integer", example="11"),
 * )
 */
class EquipmentUpdateRequest extends AbstractApiRequest
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
        $workspace_id = request()->workspace_id;
        $id = request()->ingredient;
        return [
            'name' => ['required', 'string', 'max:255', new UniqueEquipmentNameRule($workspace_id, $id)],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantity' => ['required', 'string'],
            'workspace_id' => ['required', 'integer', new BelongsToWorkSpaceRule(Equipment::class, $workspace_id, $id)],
        ];
    }
}
