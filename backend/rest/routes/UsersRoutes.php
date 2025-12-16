<?php
use OpenApi\Annotations as OA;
// All users (probably for admin only)
/**
 * @OA\Get(
 *     path="/users",
 *     tags={"users"},
 *     summary="Get all users (admin only)",
 *     description="Returns a list of all users. Intended for admin use.",
 *     @OA\Response(
 *         response=200,
 *         description="List of users",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         )
 *     )
 * )
 */
Flight::route('GET /users', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::usersService()->getAll());
});

// Public view of one user
/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Get public view of a user by ID",
 *     description="Returns a public-safe view of user data (e.g. without sensitive fields).",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User object (public view)",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::usersService()->publicById((int)$id));
});

// Get user by username (e.g. ?username=foo)
/**
 * @OA\Get(
 *     path="/user",
 *     tags={"users"},
 *     summary="Get user by username",
 *     description="Looks up a user by their username, passed as a query parameter.",
 *     @OA\Parameter(
 *         name="username",
 *         in="query",
 *         required=true,
 *         description="Username to search for",
 *         @OA\Schema(type="string", example="admin")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User object",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing or invalid username parameter",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('GET /user', function() {
    $username = Flight::request()->query['username'] ?? null;
    if (!$username) {
        Flight::json(['error' => 'username query param is required'], 400);
        return;
    }
    Flight::json(Flight::usersService()->getByUsername($username));
});

// Create user
/**
 * @OA\Post(
 *     path="/users",
 *     tags={"users"},
 *     summary="Create a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"username","email","password"},
 *             @OA\Property(property="username", type="string", example="admin"),
 *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 example="StrongPass123!",
 *                 description="Plaintext password that will be hashed on the server"
 *             ),
 *             @OA\Property(
 *                 property="role",
 *                 type="string",
 *                 nullable=true,
 *                 example="user"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

Flight::route('POST /users', function() {
    $data = Flight::request()->data->getData();

    // Validate required fields
    if (!isset($data['username'], $data['email'], $data['password'])) {
        Flight::json(['error' => 'username, email and password are required'], 400);
        return;
    }

    // Prepare data for DB
    $user = [
        'username'      => $data['username'],
        'email'         => $data['email'],
        'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
        'role'          => $data['role'] ?? 'user'
    ];

    try {
        // Insert → returns ID (not array!)
        $id = Flight::usersService()->insertUser($user);

        // Remove sensitive field before responding
        unset($user['password_hash']);
        $user['id'] = $id;

        Flight::json($user, 201);

    } catch (PDOException $e) {
        // Duplicate username or email
        if ($e->getCode() === '23000') {
            Flight::json(['error' => 'Username or email already exists'], 409);
            return;
        }

        throw $e; // other DB errors
    }
});



// Update user
/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Update an existing user",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="username", type="string", example="new_admin"),
 *             @OA\Property(property="email", type="string", format="email", nullable=true, example="new_admin@example.com"),
 *             @OA\Property(property="role", type="string", nullable=true, example="editor")
 *             // intentionally no password here – password change has a dedicated endpoint
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /users/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::usersService()->updateUser((int)$id, $data));
});

// Delete user
/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Delete a user",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id) {
    Flight::json(Flight::usersService()->deleteUser((int)$id));
});

// Update password
/**
 * @OA\Put(
 *     path="/users/{id}/password",
 *     tags={"users"},
 *     summary="Update user password",
 *     description="Updates only the user's password using a pre-hashed password value.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user whose password will be updated",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"password_hash"},
 *             @OA\Property(
 *                 property="password_hash",
 *                 type="string",
 *                 description="BCrypt (or other) hashed password",
 *                 example="$2y$10$...hashed..."
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password updated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Password updated successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing or invalid password_hash",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
Flight::route('PUT /users/@id/password', function($id) {
    $data = Flight::request()->data->getData();
    $password_hash = $data['password_hash'] ?? null;
    if (!$password_hash) {
        Flight::json(['error' => 'password_hash is required'], 400);
        return;
    }
    Flight::json(Flight::usersService()->updatePassword((int)$id, $password_hash));
});
