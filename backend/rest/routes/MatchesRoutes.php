<?php
use OpenApi\Annotations as OA;
// Matches for a given league
/**
 * @OA\Get(
 *     path="/leagues/{league_id}/matches",
 *     tags={"matches"},
 *     summary="Get all matches for a given league",
 *     @OA\Parameter(
 *         name="league_id",
 *         in="path",
 *         required=true,
 *         description="ID of the league",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of matches for the given league",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Match")
 *         )
 *     )
 * )
 */
Flight::route('GET /leagues/@league_id/matches', function($league_id) {
    Flight::json(Flight::matchesService()->listByLeague((int)$league_id));
});

// Get single match
/**
 * @OA\Get(
 *     path="/matches/{id}",
 *     tags={"matches"},
 *     summary="Get match by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the match",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match object",
 *         @OA\JsonContent(ref="#/components/schemas/Match")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /matches/@id', function($id) {
    Flight::json(Flight::matchesService()->getById($id));
});

// Create match
/**
 * @OA\Post(
 *     path="/matches",
 *     tags={"matches"},
 *     summary="Create a new match",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"league_id","home_participant_id","away_participant_id","start_time"},
 *             @OA\Property(property="league_id", type="integer", example=1),
 *             @OA\Property(property="phase_id", type="integer", nullable=true, example=2),
 *             @OA\Property(property="round", type="integer", nullable=true, example=3),
 *             @OA\Property(property="start_time", type="string", format="date-time", example="2024-11-01T18:00:00"),
 *             @OA\Property(property="venue_id", type="integer", nullable=true, example=5),
 *             @OA\Property(property="home_participant_id", type="integer", example=10),
 *             @OA\Property(property="away_participant_id", type="integer", example=11),
 *             @OA\Property(property="home_score", type="integer", nullable=true, example=28),
 *             @OA\Property(property="away_score", type="integer", nullable=true, example=25),
 *             @OA\Property(property="status", type="string", nullable=true, example="scheduled")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match created",
 *         @OA\JsonContent(ref="#/components/schemas/Match")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /matches', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::matchesService()->insertMatch($data));
});

// Update match
/**
 * @OA\Put(
 *     path="/matches/{id}",
 *     tags={"matches"},
 *     summary="Update an existing match",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the match to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="league_id", type="integer", example=1),
 *             @OA\Property(property="phase_id", type="integer", nullable=true, example=2),
 *             @OA\Property(property="round", type="integer", nullable=true, example=4),
 *             @OA\Property(property="start_time", type="string", format="date-time", example="2024-11-02T19:00:00"),
 *             @OA\Property(property="venue_id", type="integer", nullable=true, example=6),
 *             @OA\Property(property="home_participant_id", type="integer", example=10),
 *             @OA\Property(property="away_participant_id", type="integer", example=11),
 *             @OA\Property(property="home_score", type="integer", nullable=true, example=30),
 *             @OA\Property(property="away_score", type="integer", nullable=true, example=27),
 *             @OA\Property(property="status", type="string", nullable=true, example="finished")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match updated",
 *         @OA\JsonContent(ref="#/components/schemas/Match")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /matches/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::matchesService()->updateMatch((int)$id, $data));
});

// Delete match
/**
 * @OA\Delete(
 *     path="/matches/{id}",
 *     tags={"matches"},
 *     summary="Delete a match",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the match to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Match deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Match not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /matches/@id', function($id) {
    Flight::json(Flight::matchesService()->deleteMatch((int)$id));
});
