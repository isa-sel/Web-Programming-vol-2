<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/genders",
 *     tags={"genders"},
 *     summary="Get all genders",
 *     @OA\Response(
 *         response=200,
 *         description="List of genders",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Gender")
 *         )
 *     )
 * )
 */
Flight::route('GET /genders', function() {
    Flight::json(Flight::genderService()->get_all());
});

/**
 * @OA\Get(
 *     path="/genders/{id}",
 *     tags={"genders"},
 *     summary="Get gender by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the gender entry",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Gender object",
 *         @OA\JsonContent(ref="#/components/schemas/Gender")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Gender not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /genders/@id', function($id) {
    Flight::json(Flight::genderService()->get_by_id((int)$id));
});

/**
 * @OA\Post(
 *     path="/genders",
 *     tags={"genders"},
 *     summary="Create a new gender entry",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Muško"),
 *             @OA\Property(property="code", type="string", example="M")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Gender created",
 *         @OA\JsonContent(ref="#/components/schemas/Gender")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /genders', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::genderService()->insert($data));
});

/**
 * @OA\Put(
 *     path="/genders/{id}",
 *     tags={"genders"},
 *     summary="Update an existing gender entry",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the gender entry to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Žensko"),
 *             @OA\Property(property="code", type="string", example="F")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Gender updated",
 *         @OA\JsonContent(ref="#/components/schemas/Gender")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Gender not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /genders/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::genderService()->update($data, (int)$id));
});

/**
 * @OA\Delete(
 *     path="/genders/{id}",
 *     tags={"genders"},
 *     summary="Delete a gender entry",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the gender entry to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Gender deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Gender not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /genders/@id', function($id) {
    Flight::json(Flight::genderService()->delete((int)$id));
});
