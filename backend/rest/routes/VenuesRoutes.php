<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/venues",
 *     tags={"venues"},
 *     summary="Get all venues",
 *     @OA\Response(
 *         response=200,
 *         description="List of venues",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Venue")
 *         )
 *     )
 * )
 */
Flight::route('GET /venues', function() {
    Flight::json(Flight::venuesService()->getAll());
});

/**
 * @OA\Get(
 *     path="/venues/{id}",
 *     tags={"venues"},
 *     summary="Get venue by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Venue ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Venue object",
 *         @OA\JsonContent(ref="#/components/schemas/Venue")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Venue not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /venues/@id', function($id) {
    Flight::json(Flight::venuesService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/venues",
 *     tags={"venues"},
 *     summary="Create a new venue",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="SD Dobrinja"),
 *             @OA\Property(property="city", type="string", nullable=true, example="Sarajevo"),
 *             @OA\Property(property="address", type="string", nullable=true, example="Omladinska 12"),
 *             @OA\Property(property="lat", type="number", format="float", nullable=true, example=43.8292),
 *             @OA\Property(property="lng", type="number", format="float", nullable=true, example=18.3564)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Venue created",
 *         @OA\JsonContent(ref="#/components/schemas/Venue")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /venues', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::venuesService()->insertVenue($data));
});

/**
 * @OA\Put(
 *     path="/venues/{id}",
 *     tags={"venues"},
 *     summary="Update an existing venue",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the venue to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="SD Mojmilo"),
 *             @OA\Property(property="city", type="string", nullable=true, example="Sarajevo"),
 *             @OA\Property(property="address", type="string", nullable=true, example="Avde Hume bb"),
 *             @OA\Property(property="lat", type="number", format="float", nullable=true),
 *             @OA\Property(property="lng", type="number", format="float", nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Venue updated",
 *         @OA\JsonContent(ref="#/components/schemas/Venue")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Venue not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /venues/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::venuesService()->updateVenue((int)$id, $data));
});

/**
 * @OA\Delete(
 *     path="/venues/{id}",
 *     tags={"venues"},
 *     summary="Delete a venue",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Venue ID to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Venue deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Venue not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /venues/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::venuesService()->deleteVenue((int)$id));
});
