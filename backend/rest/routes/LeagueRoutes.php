<?php
use OpenApi\Annotations as OA;
// Get all leagues with lookups (age category + gender)
/**
 * @OA\Get(
 *     path="/leagues",
 *     tags={"leagues"},
 *     summary="Get all leagues with age category and gender lookups",
 *     @OA\Response(
 *         response=200,
 *         description="List of leagues with lookups",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/League")
 *         )
 *     )
 * )
 */
Flight::route('GET /leagues', function() {
    Flight::json(Flight::leagueService()->listWithLookups());
});

// Get one league by ID
/**
 * @OA\Get(
 *     path="/leagues/{id}",
 *     tags={"leagues"},
 *     summary="Get league by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="League ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="League object",
 *         @OA\JsonContent(ref="#/components/schemas/League")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="League not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /leagues/@id', function($id) {
    Flight::json(Flight::leagueService()->getById($id));
});

// Create league
/**
 * @OA\Post(
 *     path="/leagues",
 *     tags={"leagues"},
 *     summary="Create a new league",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Liga U15 - Sezona 2024/25"),
 *             @OA\Property(property="season", type="string", nullable=true, example="2024/25"),
 *             @OA\Property(property="age_category_id", type="integer", nullable=true, example=3),
 *             @OA\Property(property="gender_id", type="integer", nullable=true, example=1),
 *             @OA\Property(property="from_date", type="string", format="date", nullable=true, example="2024-09-01"),
 *             @OA\Property(property="to_date", type="string", format="date", nullable=true, example="2025-04-01")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="League created",
 *         @OA\JsonContent(ref="#/components/schemas/League")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /leagues', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::leagueService()->insertLeague($data));
});

// Update league
/**
 * @OA\Put(
 *     path="/leagues/{id}",
 *     tags={"leagues"},
 *     summary="Update an existing league",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the league to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Liga U17"),
 *             @OA\Property(property="season", type="string", nullable=true, example="2024/25"),
 *             @OA\Property(property="age_category_id", type="integer", nullable=true),
 *             @OA\Property(property="gender_id", type="integer", nullable=true),
 *             @OA\Property(property="from_date", type="string", format="date", nullable=true),
 *             @OA\Property(property="to_date", type="string", format="date", nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="League updated",
 *         @OA\JsonContent(ref="#/components/schemas/League")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="League not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /leagues/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::leagueService()->updateLeague((int)$id, $data));
});

// Delete league
/**
 * @OA\Delete(
 *     path="/leagues/{id}",
 *     tags={"leagues"},
 *     summary="Delete a league",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="League ID to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="League deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="League not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /leagues/@id', function($id) {
    Flight::json(Flight::leagueService()->deleteLeague((int)$id));
});
