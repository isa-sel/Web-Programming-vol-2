<?php
use OpenApi\Annotations as OA;
// All phases for a given league
/**
 * @OA\Get(
 *     path="/leagues/{league_id}/phases",
 *     tags={"league-phases"},
 *     summary="Get all phases for a given league",
 *     @OA\Parameter(
 *         name="league_id",
 *         in="path",
 *         required=true,
 *         description="ID of the league",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of phases for the given league",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/LeaguePhase")
 *         )
 *     )
 * )
 */
Flight::route('GET /leagues/@league_id/phases', function($league_id) {
    Flight::json(Flight::leaguePhaseService()->listByLeague((int)$league_id));
});

// Get single phase
/**
 * @OA\Get(
 *     path="/phases/{id}",
 *     tags={"league-phases"},
 *     summary="Get single phase by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the phase",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Phase object",
 *         @OA\JsonContent(ref="#/components/schemas/LeaguePhase")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Phase not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /phases/@id', function($id) {
    Flight::json(Flight::leaguePhaseService()->getById($id));
});

// Create phase
/**
 * @OA\Post(
 *     path="/phases",
 *     tags={"league-phases"},
 *     summary="Create a new phase",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"league_id","name"},
 *             @OA\Property(property="league_id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Ligaški dio"),
 *             @OA\Property(property="order_no", type="integer", nullable=true, example=1),
 *             @OA\Property(property="is_playoff", type="boolean", nullable=true, example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Phase created",
 *         @OA\JsonContent(ref="#/components/schemas/LeaguePhase")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /phases', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::leaguePhaseService()->insertPhase($data));
});

// Update phase
/**
 * @OA\Put(
 *     path="/phases/{id}",
 *     tags={"league-phases"},
 *     summary="Update an existing phase",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the phase to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="league_id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Playoff"),
 *             @OA\Property(property="order_no", type="integer", nullable=true, example=2),
 *             @OA\Property(property="is_playoff", type="boolean", nullable=true, example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Phase updated",
 *         @OA\JsonContent(ref="#/components/schemas/LeaguePhase")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Phase not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /phases/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::leaguePhaseService()->updatePhase((int)$id, $data));
});

// Delete phase
/**
 * @OA\Delete(
 *     path="/phases/{id}",
 *     tags={"league-phases"},
 *     summary="Delete a phase",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the phase to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Phase deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Phase not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /phases/@id', function($id) {
    Flight::json(Flight::leaguePhaseService()->deletePhase((int)$id));
});
