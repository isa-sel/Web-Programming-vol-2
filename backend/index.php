<?php


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication, X-Requested-With');
header('Access-Control-Max-Age: 86400');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}



require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/data/roles.php';

// Services
require_once __DIR__ . '/rest/services/AuthService.php';
require_once __DIR__ . '/rest/services/LeagueService.php';
require_once __DIR__ . '/rest/services/LeaguePhaseService.php';
require_once __DIR__ . '/rest/services/TeamsService.php';
require_once __DIR__ . '/rest/services/PlayersService.php';
require_once __DIR__ . '/rest/services/MatchesService.php';
require_once __DIR__ . '/rest/services/TeamPlayerService.php';
require_once __DIR__ . '/rest/services/ParticipantsService.php';
require_once __DIR__ . '/rest/services/VenuesService.php';
require_once __DIR__ . '/rest/services/AgeCategoryService.php';
require_once __DIR__ . '/rest/services/GenderService.php';
require_once __DIR__ . '/rest/services/UsersService.php';

require_once __DIR__ . '/middleware/AuthMiddleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Register services
Flight::register('auth_service', "AuthService");
Flight::register('leagueService', 'LeagueService');
Flight::register('leaguePhaseService', 'LeaguePhaseService');
Flight::register('teamsService', 'TeamsService');
Flight::register('playersService', 'PlayersService');
Flight::register('matchesService', 'MatchesService');
Flight::register('teamPlayerService', 'TeamPlayerService');
Flight::register('participantsService', 'ParticipantsService');
Flight::register('venuesService', 'VenuesService');
Flight::register('ageCategoryService', 'AgeCategoryService');
Flight::register('genderService', 'GenderService');
Flight::register('usersService', 'UsersService');

Flight::register('auth_middleware', "AuthMiddleware");


Flight::route('/*', function() {
   if(
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0
   ) {
       return TRUE;
   } else {
       try {
           $token = Flight::request()->getHeader("Authentication");
           if(Flight::auth_middleware()->verifyToken($token))
               return TRUE;
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});



// Routes
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/LeagueRoutes.php';
require_once __DIR__ . '/rest/routes/LeaguePhaseRoutes.php';
require_once __DIR__ . '/rest/routes/TeamsRoutes.php';
require_once __DIR__ . '/rest/routes/PlayersRoutes.php';
require_once __DIR__ . '/rest/routes/MatchesRoutes.php';
require_once __DIR__ . '/rest/routes/TeamPlayerRoutes.php';
require_once __DIR__ . '/rest/routes/ParticipantsRoutes.php';
require_once __DIR__ . '/rest/routes/VenuesRoutes.php';
require_once __DIR__ . '/rest/routes/AgeCategoryRoutes.php';
require_once __DIR__ . '/rest/routes/GenderRoutes.php';
require_once __DIR__ . '/rest/routes/UsersRoutes.php';

// Test route
Flight::route('GET /', function() {
    echo 'Handball API is running';
});

Flight::start();
?>
