<?php

namespace App\Modules\Sections;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ControllerTraits;
use App\Modules\Sections\Requests\SectionStoreRequest;
use App\Modules\Sections\Requests\SectionUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SectionController extends Controller
{
    use ControllerTraits;
    /**
     * @OA\Get(
     *     path="/api/section",
     *     summary="Get a sections of workspace by ID of workspace",
     *     tags={"Sections"},
     *     security={{"Bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of sections of workspaces retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Sections")
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
    public function index()
    {
        try {
            $workspace_id = request()->user()->workspace_id;
            $sections = Section::where('workspace_id',$workspace_id)->get();
            return ApiResponse::success($sections);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('this section not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/section",
     *     summary="Create a new section",
     *     tags={"Sections"},
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SectionStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Section created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Section created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Sections"
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
    public function store(SectionStoreRequest $request)
    {
        try {
            $section = Section::create([
                'title' => $request->title,
                'workspace_id' => request()->user()->workspace_id
            ]);
            return ApiResponse::created($section);
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }


   /**
     * @OA\Get(
     *     path="/api/section/{id}",
     *     summary="Get a section by ID",
     *     tags={"Sections"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the section",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Section retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Sections"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Section not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Section not found"),
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
            $section = Section::userWorkspace()->where('id', $id)->firstOrFail();
            return ApiResponse::success($section);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Section not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }

    /**
     * @OA\Put(
     *     path="/api/section/{id}",
     *     summary="Update a section",
     *     tags={"Sections"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the section",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SectionUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Section updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Sections"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Section not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Section not found"),
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
    public function update(SectionUpdateRequest $request, $id)
    {
        try {
            $section = Section::userWorkspace()->where('id', $id)->firstOrFail();
            $data = $this->updatedDataFormated($request);
            $section->fill($data);
            if($section->isDirty()){
                $section->save();
                return ApiResponse::updated($section);
            }
            return ApiResponse::message('no changes made');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Section not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }



    /**
     * @OA\Delete(
     *     path="/api/section/{id}",
     *     summary="Delete a section",
     *     tags={"Sections"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the section",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Section deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Section deleted successfully"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Section not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Section not found"),
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
            $section = Section::userWorkspace()->where('id', $id)->firstOrFail();
            $section->delete();
            return ApiResponse::message('Section deleted successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Section not found');
        } catch (\Exception $e) {
            return ApiResponse::serverError();
        }
    }
}
