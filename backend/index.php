<?php
require __DIR__ . '/vendor/autoload.php';

// Services
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

// Register services
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

// Routes
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
