<?php
use OpenApi\Annotations as OA;
// All match phases
/**
 * @OA\Get(
 *     path="/match-phases",
 *     tags={"match-phases"},
 *     summary="Get all match phases",
 *     @OA\Response(
 *         response=200,
 *         description="List of match phases",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/MatchPhase")
 *         )
 *     )
 * )
 */
Flight::route('GET /match-phases', function() {
    Flight::json(Flight::matchPhaseService()->getAll());
});

// Single match phase
/**
 * @OA\Get(
 *     path="/match-phases/{id}",
 *     tags={"match-phases"},
 *     summary="Get match phase by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Match phase ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match phase object",
 *         @OA\JsonContent(ref="#/components/schemas/MatchPhase")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match phase not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /match-phases/@id', function($id) {
    Flight::json(Flight::matchPhaseService()->getById($id));
});

// Create phase
/**
 * @OA\Post(
 *     path="/match-phases",
 *     tags={"match-phases"},
 *     summary="Create a new match phase",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="1. poluvrijeme"),
 *             @OA\Property(property="order_no", type="integer", nullable=true, example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match phase created",
 *         @OA\JsonContent(ref="#/components/schemas/MatchPhase")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /match-phases', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::matchPhaseService()->insertPhase($data));
});

// Update phase
/**
 * @OA\Put(
 *     path="/match-phases/{id}",
 *     tags={"match-phases"},
 *     summary="Update an existing match phase",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the match phase to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="2. poluvrijeme"),
 *             @OA\Property(property="order_no", type="integer", nullable=true, example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match phase updated",
 *         @OA\JsonContent(ref="#/components/schemas/MatchPhase")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match phase not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /match-phases/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::matchPhaseService()->updatePhase((int)$id, $data));
});

// Delete phase
/**
 * @OA\Delete(
 *     path="/match-phases/{id}",
 *     tags={"match-phases"},
 *     summary="Delete a match phase",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the match phase to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match phase deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match phase not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /match-phases/@id', function($id) {
    Flight::json(Flight::matchPhaseService()->deletePhase((int)$id));
});
