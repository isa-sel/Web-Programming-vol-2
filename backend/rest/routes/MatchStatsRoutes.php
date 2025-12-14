<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/match-stats",
 *     tags={"match-stats"},
 *     summary="Get all match statistics entries",
 *     @OA\Response(
 *         response=200,
 *         description="List of match statistics entries",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/MatchStats")
 *         )
 *     )
 * )
 */
Flight::route('GET /match-stats', function() {
    Flight::json(Flight::matchStatsService()->getAll());
});

/**
 * @OA\Get(
 *     path="/match-stats/{id}",
 *     tags={"match-stats"},
 *     summary="Get match statistics entry by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Match statistics ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match stats object",
 *         @OA\JsonContent(ref="#/components/schemas/MatchStats")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match statistics entry not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /match-stats/@id', function($id) {
    Flight::json(Flight::matchStatsService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/match-stats",
 *     tags={"match-stats"},
 *     summary="Create a new match statistics entry",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"match_id","match_phase_id","participant_id"},
 *             @OA\Property(property="match_id", type="integer", example=12),
 *             @OA\Property(property="match_phase_id", type="integer", example=1),
 *             @OA\Property(property="participant_id", type="integer", example=34),
 *             @OA\Property(property="goals", type="integer", nullable=true, example=5),
 *             @OA\Property(property="yellow_cards", type="integer", nullable=true, example=1),
 *             @OA\Property(property="two_minute_suspensions", type="integer", nullable=true, example=2),
 *             @OA\Property(property="red_card", type="boolean", nullable=true, example=false),
 *             @OA\Property(property="notes", type="string", nullable=true, example="Strong performance"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match stats created",
 *         @OA\JsonContent(ref="#/components/schemas/MatchStats")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /match-stats', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::matchStatsService()->insertMatchStats($data));
});

/**
 * @OA\Put(
 *     path="/match-stats/{id}",
 *     tags={"match-stats"},
 *     summary="Update an existing match statistics entry",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the match statistics entry to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="match_id", type="integer", example=12),
 *             @OA\Property(property="match_phase_id", type="integer", example=2),
 *             @OA\Property(property="participant_id", type="integer", example=34),
 *             @OA\Property(property="goals", type="integer", example=6),
 *             @OA\Property(property="yellow_cards", type="integer", nullable=true, example=0),
 *             @OA\Property(property="two_minute_suspensions", type="integer", nullable=true, example=1),
 *             @OA\Property(property="red_card", type="boolean", example=false),
 *             @OA\Property(property="notes", type="string", nullable=true, example="Updated after review")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match stats updated",
 *         @OA\JsonContent(ref="#/components/schemas/MatchStats")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match stats entry not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /match-stats/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::matchStatsService()->updateMatchStats((int)$id, $data));
});

/**
 * @OA\Delete(
 *     path="/match-stats/{id}",
 *     tags={"match-stats"},
 *     summary="Delete a match statistics entry",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the match statistics entry to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match stats deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match stats entry not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /match-stats/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::matchStatsService()->deleteMatchStats((int)$id));
});
