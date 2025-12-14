<?php
use OpenApi\Annotations as OA;
// Participants by phase
/**
 * @OA\Get(
 *     path="/phases/{phase_id}/participants",
 *     tags={"participants"},
 *     summary="Get all participants for a given phase",
 *     @OA\Parameter(
 *         name="phase_id",
 *         in="path",
 *         required=true,
 *         description="ID of the league phase",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of participants for the given phase",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Participant")
 *         )
 *     )
 * )
 */
Flight::route('GET /phases/@phase_id/participants', function($phase_id) {
    Flight::json(Flight::participantsService()->listByPhase((int)$phase_id));
});

// Get participant
/**
 * @OA\Get(
 *     path="/participants/{id}",
 *     tags={"participants"},
 *     summary="Get participant by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Participant ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Participant object",
 *         @OA\JsonContent(ref="#/components/schemas/Participant")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Participant not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /participants/@id', function($id) {
    Flight::json(Flight::participantsService()->getById($id));
});

// Create participant
/**
 * @OA\Post(
 *     path="/participants",
 *     tags={"participants"},
 *     summary="Create a new participant",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"phase_id"},
 *             @OA\Property(property="phase_id", type="integer", example=1),
 *             @OA\Property(property="team_id", type="integer", nullable=true, example=10),
 *             @OA\Property(property="pair_id", type="integer", nullable=true, example=5),
 *             @OA\Property(property="name", type="string", nullable=true, example="RK Briješće U15"),
 *             @OA\Property(property="seed_no", type="integer", nullable=true, example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Participant created",
 *         @OA\JsonContent(ref="#/components/schemas/Participant")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('POST /participants', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::participantsService()->insertParticipant($data));
});

// Update participant
/**
 * @OA\Put(
 *     path="/participants/{id}",
 *     tags={"participants"},
 *     summary="Update an existing participant",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the participant to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="phase_id", type="integer", example=1),
 *             @OA\Property(property="team_id", type="integer", nullable=true, example=11),
 *             @OA\Property(property="pair_id", type="integer", nullable=true, example=6),
 *             @OA\Property(property="name", type="string", nullable=true, example="RK Briješće U17"),
 *             @OA\Property(property="seed_no", type="integer", nullable=true, example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Participant updated",
 *         @OA\JsonContent(ref="#/components/schemas/Participant")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Participant not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /participants/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::participantsService()->updateParticipant((int)$id, $data));
});

// Delete participant
/**
 * @OA\Delete(
 *     path="/participants/{id}",
 *     tags={"participants"},
 *     summary="Delete a participant",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the participant to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Participant deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Participant not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /participants/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::participantsService()->deleteParticipant((int)$id));
});
