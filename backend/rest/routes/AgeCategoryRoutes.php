<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/age-categories",
 *     tags={"age-categories"},
 *     summary="Get all age categories",
 *     @OA\Response(
 *         response=200,
 *         description="List of age categories",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/AgeCategory")
 *         )
 *     )
 * )
 */
Flight::route('GET /age-categories', function() {
    Flight::json(Flight::ageCategoryService()->getAll());
});

/**
 * @OA\Get(
 *     path="/age-categories/{id}",
 *     tags={"age-categories"},
 *     summary="Get age category by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the age category",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Age category object",
 *         @OA\JsonContent(ref="#/components/schemas/AgeCategory")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Age category not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /age-categories/@id', function($id) {
    Flight::json(Flight::ageCategoryService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/age-categories",
 *     tags={"age-categories"},
 *     summary="Create a new age category",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="U15"),
 *             @OA\Property(property="from_year", type="integer", nullable=true, example=2010),
 *             @OA\Property(property="to_year", type="integer", nullable=true, example=2011)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Age category created",
 *         @OA\JsonContent(ref="#/components/schemas/AgeCategory")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /age-categories', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::ageCategoryService()->insertAgeCategory($data));
});

/**
 * @OA\Put(
 *     path="/age-categories/{id}",
 *     tags={"age-categories"},
 *     summary="Update an existing age category",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the age category to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="U17"),
 *             @OA\Property(property="from_year", type="integer", nullable=true, example=2008),
 *             @OA\Property(property="to_year", type="integer", nullable=true, example=2009)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Age category updated",
 *         @OA\JsonContent(ref="#/components/schemas/AgeCategory")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Age category not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /age-categories/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::ageCategoryService()->updateAgeCategory((int)$id, $data));
});

/**
 * @OA\Delete(
 *     path="/age-categories/{id}",
 *     tags={"age-categories"},
 *     summary="Delete an age category",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the age category to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Age category deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Age category not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /age-categories/@id', function($id) {
    Flight::json(Flight::ageCategoryService()->deleteAgeCategory((int)$id));
});
