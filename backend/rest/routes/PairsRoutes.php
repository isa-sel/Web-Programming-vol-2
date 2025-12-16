<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/pairs",
 *     tags={"pairs"},
 *     summary="Get all pairs",
 *     @OA\Response(
 *         response=200,
 *         description="List of pairs",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Pair")
 *         )
 *     )
 * )
 */
Flight::route('GET /pairs', function() {
    Flight::json(Flight::pairsService()->getAll());
});

/**
 * @OA\Get(
 *     path="/pairs/{id}",
 *     tags={"pairs"},
 *     summary="Get pair by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Pair ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Pair object",
 *         @OA\JsonContent(ref="#/components/schemas/Pair")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Pair not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /pairs/@id', function($id) {
    Flight::json(Flight::pairsService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/pairs",
 *     tags={"pairs"},
 *     summary="Create a new pair",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", nullable=true, example="Quarterfinal Pair 1"),
 *             @OA\Property(property="home_team_id", type="integer", nullable=true, example=10),
 *             @OA\Property(property="away_team_id", type="integer", nullable=true, example=11)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Pair created",
 *         @OA\JsonContent(ref="#/components/schemas/Pair")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /pairs', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::pairsService()->insertPair($data));
});

/**
 * @OA\Put(
 *     path="/pairs/{id}",
 *     tags={"pairs"},
 *     summary="Update an existing pair",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the pair to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", nullable=true, example="Updated Pair Name"),
 *             @OA\Property(property="home_team_id", type="integer", nullable=true, example=12),
 *             @OA\Property(property="away_team_id", type="integer", nullable=true, example=14)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Pair updated",
 *         @OA\JsonContent(ref="#/components/schemas/Pair")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Pair not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /pairs/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::pairsService()->updatePair((int)$id, $data));
});

/**
 * @OA\Delete(
 *     path="/pairs/{id}",
 *     tags={"pairs"},
 *     summary="Delete a pair",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the pair to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Pair deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Pair not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /pairs/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::pairsService()->deletePair((int)$id));
});
