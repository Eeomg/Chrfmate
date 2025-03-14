<?php

namespace App\Modules\Equipments\Requests;

use App\Http\Requests\AbstractApiRequest;
use App\Modules\Equipments\Rules\UniqueEquipmentNameRule;
use App\Modules\Warehouses\Rules\ExistsWarehouseRule;
use App\Modules\Workspaces\Rules\ExistsWorkSpaceRule;

/**
 * @OA\Schema(
 *     schema="EquipmentStoreRequest",
 *     type="object",
 *     required={"name", "unit", "quantity", "workspace_id"},
 *     @OA\Property(property="name", type="string", example="dish"),
 *     @OA\Property(property="cover", type="binary", example="image.jpg"),
 *     @OA\Property(property="description", type="string", example="some decription"),
 *     @OA\Property(property="quantity", type="integer", example="11"),
 *     @OA\Property(property="warehouse_id", type="string", example="1", description="ID of the warehouse"),
 * )
 */
class EquipmentStoreRequest extends AbstractApiRequest
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
        $workspace_id = request()->user()->workspace_id;
        return [
            'warehouse_id' => ['nullable', 'integer', new ExistsWarehouseRule()],
            'name' => ['required', 'string', 'max:255',new UniqueEquipmentNameRule($workspace_id)],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantity' => ['required', 'integer'],
        ];

    }
}
