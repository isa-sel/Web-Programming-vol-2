<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/players",
 *     tags={"players"},
 *     summary="Get all players",
 *     @OA\Response(
 *         response=200,
 *         description="List of players",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Player")
 *         )
 *     )
 * )
 */
Flight::route('GET /players', function() {
    Flight::json(Flight::playersService()->getAll());
});

/**
 * @OA\Get(
 *     path="/players/{id}",
 *     tags={"players"},
 *     summary="Get player by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Player ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Player object",
 *         @OA\JsonContent(ref="#/components/schemas/Player")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Player not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /players/@id', function($id) {
    Flight::json(Flight::playersService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/players",
 *     tags={"players"},
 *     summary="Create a new player",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="first_name", type="string", example="Ahmed"),
 *             @OA\Property(property="last_name", type="string", example="Selimović"),
 *             @OA\Property(property="date_of_birth", type="string", format="date", nullable=true, example="2010-03-15"),
 *             @OA\Property(property="shirt_number", type="integer", nullable=true, example=9),
 *             @OA\Property(property="position", type="string", nullable=true, example="LB"),
 *             @OA\Property(property="team_id", type="integer", nullable=true, example=5)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Player created",
 *         @OA\JsonContent(ref="#/components/schemas/Player")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /players', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::playersService()->insertPlayer($data));
});

/**
 * @OA\Put(
 *     path="/players/{id}",
 *     tags={"players"},
 *     summary="Update an existing player",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the player to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="first_name", type="string", example="Amar"),
 *             @OA\Property(property="last_name", type="string", example="Selimović"),
 *             @OA\Property(property="date_of_birth", type="string", format="date", nullable=true),
 *             @OA\Property(property="shirt_number", type="integer", nullable=true, example=11),
 *             @OA\Property(property="position", type="string", nullable=true, example="CB"),
 *             @OA\Property(property="team_id", type="integer", nullable=true, example=4)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Player updated",
 *         @OA\JsonContent(ref="#/components/schemas/Player")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Player not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /players/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::playersService()->updatePlayer((int)$id, $data));
});

/**
 * @OA\Delete(
 *     path="/players/{id}",
 *     tags={"players"},
 *     summary="Delete a player",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Player ID to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Player deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Player not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /players/@id', function($id) {
    Flight::json(Flight::playersService()->deletePlayer((int)$id));
});
