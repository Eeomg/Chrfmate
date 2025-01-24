<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Facades\ApiResponse;
use App\Http\Requests\Warehouse\WarehouseStoreRequest;
use App\Http\Requests\Warehouse\WarehouseUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WarehouseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/workspaces/{id}/warehouse/{type}",
     *     summary="Get a warehouse details of workspace by ID of workspace",
     *     tags={"Warehouse"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the workspace",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         required=true,
     *         description="equipment || ingredient",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of Contents of warehouse of workspaces retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Warehouse")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function index($workspace_id,$type)
    {
        try {
            $warehoouse = Warehouse::userWorkspace()
                            ->where('workspace_id',$workspace_id)
                            ->where('type',$type)
                            ->get();
            return ApiResponse::success($warehoouse);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('this warehouse not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/warehouse",
     *     summary="Create a new warehouse Inventory",
     *     tags={"Warehouse"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/WarehouseStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="successfully added",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="successfully added"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Warehouse"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation Error"),
     *             @OA\Property(property="errors", type="object", example={"name": {"The title field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function store(WarehouseStoreRequest $request)
    {
        try {
            $warehouse = Warehouse::create($request->all());
            return ApiResponse::created($warehouse);
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/warehouse/{id}",
     *     summary="Update a warehouse",
     *     tags={"Warehouse"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Warehouse",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/WarehouseUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Warehouse updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Warehouse"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Section not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Inventory not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function update(WarehouseUpdateRequest $request, $id)
    {
        try {
            $inventory  = Warehouse::userWorkspace()->where('id', $id)->firstOrFail();
            $data = $this->updatedDataFormated($request);
            $inventory ->fill($data);
            if($inventory ->isDirty()){
                $inventory ->save();
                return ApiResponse::updated($inventory );
            }
            return ApiResponse::message('no changes made');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Inventory  not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }



    /**
     * @OA\Delete(
     *     path="/api/warehouse/{id}",
     *     summary="Delete a Inventory",
     *     tags={"Warehouse"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Inventory ",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inventory deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Inventory deleted successfully"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Inventory not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Inventory  not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            Warehouse::userWorkspace()->where('id', $id)->firstOrFail()->delete();
            return ApiResponse::message('Inventory deleted successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Inventory not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }
}
