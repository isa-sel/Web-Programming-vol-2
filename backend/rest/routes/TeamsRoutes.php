<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Get(
 *     path="/teams",
 *     tags={"teams"},
 *     summary="Get all teams with lookups (age category + gender)",
 *     @OA\Response(
 *         response=200,
 *         description="List of teams with lookups",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Team")
 *         )
 *     )
 * )
 */
Flight::route('GET /teams', function() {
    Flight::json(Flight::teamsService()->listWithLookups());
});

/**
 * @OA\Get(
 *     path="/teams/{id}",
 *     tags={"teams"},
 *     summary="Get team by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Team ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team object",
 *         @OA\JsonContent(ref="#/components/schemas/Team")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Team not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /teams/@id', function($id) {
    Flight::json(Flight::teamsService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/teams",
 *     tags={"teams"},
 *     summary="Create a new team",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="RK Briješće U15"),
 *             @OA\Property(property="short_name", type="string", nullable=true, example="BRI U15"),
 *             @OA\Property(property="club_name", type="string", nullable=true, example="RK Briješće"),
 *             @OA\Property(property="age_category_id", type="integer", nullable=true, example=3),
 *             @OA\Property(property="gender_id", type="integer", nullable=true, example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team created",
 *         @OA\JsonContent(ref="#/components/schemas/Team")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /teams', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::teamsService()->insertTeam($data));
});

/**
 * @OA\Put(
 *     path="/teams/{id}",
 *     tags={"teams"},
 *     summary="Update an existing team",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the team to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="RK Briješće U17"),
 *             @OA\Property(property="short_name", type="string", nullable=true, example="BRI U17"),
 *             @OA\Property(property="club_name", type="string", nullable=true),
 *             @OA\Property(property="age_category_id", type="integer", nullable=true),
 *             @OA\Property(property="gender_id", type="integer", nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team updated",
 *         @OA\JsonContent(ref="#/components/schemas/Team")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Team not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /teams/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::teamsService()->updateTeam((int)$id, $data));
});

/**
 * @OA\Delete(
 *     path="/teams/{id}",
 *     tags={"teams"},
 *     summary="Delete a team",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Team ID to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Team deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Team not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /teams/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::teamsService()->deleteTeam((int)$id));
});
