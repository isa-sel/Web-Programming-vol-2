<?php
use OpenApi\Annotations as OA;
// Current roster for a team on given date (?date=YYYY-MM-DD, default: today)
/**
 * @OA\Get(
 *     path="/teams/{team_id}/roster",
 *     tags={"teams"},
 *     summary="Get current roster for a team on a given date",
 *     @OA\Parameter(
 *         name="team_id",
 *         in="path",
 *         required=true,
 *         description="ID of the team",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         required=false,
 *         description="Roster date in format YYYY-MM-DD (default: today)",
 *         @OA\Schema(type="string", format="date", example="2024-11-01")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Current roster for the given date",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/TeamPlayer")
 *         )
 *     )
 * )
 */
Flight::route('GET /teams/@team_id/roster', function($team_id) {
    $date = Flight::request()->query['date'] ?? date('Y-m-d');
    Flight::json(Flight::teamPlayerService()->currentRoster((int)$team_id, $date));
});

// Add team-player relation
/**
 * @OA\Post(
 *     path="/team-player",
 *     tags={"team-players"},
 *     summary="Create a new team-player relation (add player to team roster)",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"team_id","player_id"},
 *             @OA\Property(property="team_id", type="integer", example=5),
 *             @OA\Property(property="player_id", type="integer", example=12),
 *             @OA\Property(property="shirt_number", type="integer", nullable=true, example=9),
 *             @OA\Property(property="position", type="string", nullable=true, example="GK"),
 *             @OA\Property(property="is_captain", type="boolean", nullable=true, example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team-player relation created",
 *         @OA\JsonContent(ref="#/components/schemas/TeamPlayer")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /team-player', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::teamPlayerService()->insertTeamPlayer($data));
});

// Update team-player relation
/**
 * @OA\Put(
 *     path="/team-player/{id}",
 *     tags={"team-players"},
 *     summary="Update an existing team-player relation",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the team-player relation to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="team_id", type="integer", nullable=true, example=5),
 *             @OA\Property(property="player_id", type="integer", nullable=true, example=12),
 *             @OA\Property(property="shirt_number", type="integer", nullable=true, example=1),
 *             @OA\Property(property="position", type="string", nullable=true, example="CB"),
 *             @OA\Property(property="is_captain", type="boolean", nullable=true, example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team-player relation updated",
 *         @OA\JsonContent(ref="#/components/schemas/TeamPlayer")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Team-player relation not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /team-player/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::teamPlayerService()->updateTeamPlayer((int)$id, $data));
});

// Delete team-player relation
/**
 * @OA\Delete(
 *     path="/team-player/{id}",
 *     tags={"team-players"},
 *     summary="Delete a team-player relation (remove player from team roster)",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the team-player relation to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team-player relation deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Team-player relation not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /team-player/@id', function($id) {
    Flight::json(Flight::teamPlayerService()->deleteTeamPlayer((int)$id));
});
