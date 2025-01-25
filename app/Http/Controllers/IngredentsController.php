<?php

namespace App\Http\Controllers;

use App\Models\Ingredent;
use App\Facades\ApiResponse;
use App\Facades\FileHandeler;
use App\Http\Resources\IngredientsResources;
use App\Http\Controllers\Traits\ControllerTraits;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Ingredients\IngredientStoreRequest;
use App\Http\Requests\Ingredients\IngredientUpdateRequest;

class IngredentsController extends Controller
{

    use ControllerTraits;

    /**
     * @OA\Get(
     *     path="/api/workspaces/{id}/ingredients",
     *     summary="Get a ingredients of workspace by ID of workspace",
     *     tags={"Ingredients"},
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
     *         required=false,
     *         description="type of ingredients",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of ingredients retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Ingredients")
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
    public function index($workspace_id)
    {
        try {
            $ingredients = Ingredent::with(['warehouse','workspace'])
                ->userWorkspace($workspace_id)
                ->WarehouseFilter()->get();
                return ApiResponse::success(IngredientsResources::collection($ingredients));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/ingredient",
     *     summary="Create a new ingredient",
     *     tags={"Ingredients"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IngredientStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ingredient created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Ingredient created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Ingredients"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation Error"),
     *             @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
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
    public function store(IngredientStoreRequest $request)
    {
        try {
            if($request->has('cover')){
                $cover = FileHandeler::storeFile($request->cover, 'ingredients', 'jpg');
                $request->merge(['cover' => $cover]);
            }
            $ingredient = Ingredent::create($request->all());
            FileHandeler::storeFile($ingredient);
            return ApiResponse::created(new IngredientsResources($ingredient));
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }


   /**
     * @OA\Get(
     *     path="/api/ingredient/{id}",
     *     summary="Get a ingredient by ID",
     *     tags={"Ingredients"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the ingredient",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ingredient retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Ingredients"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ingredient not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ingredient not found"),
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
    public function show($id)
    {
        try {
            $ingredient = Ingredent::userWorkspace()->where('id', $id)->firstOrFail();
            return ApiResponse::success(new IngredientsResources($ingredient));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Ingredient not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }

   /**
     * @OA\Put(
     *     path="/api/ingredient/{id}",
     *     summary="Update a ingredient",
     *     tags={"Ingredients"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the ingredient",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IngredientUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ingredient updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Ingredients"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ingredient not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ingredient not found"),
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
    public function update(IngredientUpdateRequest $request, $id)
    {
        try {
            $ingredient = Ingredent::userWorkspace()->where('id', $id)->firstOrFail();
            $data = $this->updateWithFile('cover', $request, $ingredient, 'ingredients');
            $ingredient->fill($data);
            if($ingredient->isDirty()){
                $ingredient->save();
                return ApiResponse::updated(new IngredientsResources($ingredient));
            }
            return ApiResponse::message('no changes made');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('ingredient not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }




/**
     * @OA\Delete(
     *     path="/api/ingredient/{id}",
     *     summary="Delete a ingredient",
     *     tags={"Ingredients"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the ingredient",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ingredient deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ingredient deleted successfully"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ingredient not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ingredient not found"),
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
            Ingredent::userWorkspace()->where('id', $id)->firstOrFail()->delete();
            return ApiResponse::message('ingredient deleted successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('ingredient not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }
}
