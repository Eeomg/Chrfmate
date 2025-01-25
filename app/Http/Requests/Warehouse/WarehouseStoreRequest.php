<?php

namespace App\Http\Requests\Warehouse;

use App\Models\Warehouse;
use App\Rules\UniqueTitleRole;
use App\Rules\ExistsWorkSpaceRole;
use App\Rules\UniqueWarehouseTitleRule;
use App\Http\Requests\AbstractApiRequest;

/**
 * @OA\Schema(
 *     schema="WarehouseStoreRequest",
 *     type="object",
 *     required={"title", "type", "workspace_id"},
 *     @OA\Property(property="title", type="string", example="flavours"),
 *     @OA\Property(property="type", type="string", example="equipment || ingredient", description="type of Inventory"),
 *     @OA\Property(property="workspace_id", type="string", example="1", description="ID of the workspace")
 * )
 */
class WarehouseStoreRequest extends AbstractApiRequest
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
        $workspace = request()->workspace_id;
        return [
            'title' => ['required', 'string', 'max:255', new UniqueWarehouseTitleRule($workspace)],
            'type' => ['required', 'string', 'in:equipment,ingredient'],
            'workspace_id' => ['required', 'integer', new ExistsWorkSpaceRole()],
        ];
    }
}
