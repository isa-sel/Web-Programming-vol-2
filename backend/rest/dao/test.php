<?php
require_once __DIR__ . '/UsersDao.php';
require_once __DIR__ . '/VenuesDao.php';
require_once __DIR__ . '/AgeCategoryDao.php';
require_once __DIR__ . '/GenderDao.php';
require_once __DIR__ . '/TeamsDao.php';
require_once __DIR__ . '/PlayersDao.php';
require_once __DIR__ . '/LeagueDao.php';
require_once __DIR__ . '/LeaguePhaseDao.php';
require_once __DIR__ . '/ParticipantsDao.php';
require_once __DIR__ . '/PairsDao.php';
require_once __DIR__ . '/MatchesDao.php';

try {
  $gDao = new GenderDao();
  if (count($gDao->getAll()) === 0) {
    $gDao->insert(['name' => 'Male']);
    $gDao->insert(['name' => 'Female']);
  }

  $acDao = new AgeCategoryDao();
  if (count($acDao->getAll()) === 0) {
    $acDao->insert(['name' => 'U15', 'min_age' => 13, 'max_age' => 15]);
  }

  $users = new UsersDao();
  $uid = $users->insert([
    'username' => 'admin_'.uniqid(),
    'email' => 'admin'.uniqid().'@ex.com',
    'password_hash' => password_hash('secret', PASSWORD_BCRYPT),
    'role' => 'admin'
  ]);
  echo "User created: $uid\n";

  $venues = new VenuesDao();
  $vid = $venues->insert(['name' => 'Main Hall']);
  echo "Venue created: $vid\n";

  $gender_id = $gDao->getAll()[0]['id'];
  $age_id = $acDao->getAll()[0]['id'];

  $teams = new TeamsDao();
  $tid = $teams->insert(['name' => 'RK Briješće U15', 'age_category_id' => $age_id, 'gender_id' => $gender_id]);

  $players = new PlayersDao();
  $pid = $players->insert([
    'name' => 'Player One', 'birthday' => '2011-05-10', 'gender_id' => $gender_id,
    'height' => 175.0, 'weight' => 66.0
  ]);

  $league = new LeagueDao();
  $lid = $league->insert([
    'name' => 'U15 League 2025', 'age_category_id' => $age_id, 'gender_id' => $gender_id,
    'from_date' => '2025-09-01', 'until_date' => null
  ]);

  $lpDao = new LeaguePhaseDao();
  $lpid = $lpDao->insert([
    'parent_id' => null, 'league_id' => $lid, 'name' => 'Regular Season',
    'playing_mode' => 'group', 'matches_per_participant' => 2
  ]);

  $parts = new ParticipantsDao();
  $pHome = $parts->insert([
    'league_phase_id' => $lpid, 'name' => 'RK Briješće U15', 'team_id' => $tid, 'team_name' => 'RK Briješće U15'
  ]);
  $pAway = $parts->insert([
    'league_phase_id' => $lpid, 'name' => 'RK Vogošća U15', 'team_id' => null, 'team_name' => 'RK Vogošća U15'
  ]);

  $pairs = new PairsDao();
  $pairId = $pairs->insert(['participant_A_id' => $pHome, 'participant_B_id' => $pAway]);

  $matches = new MatchesDao();
  $mid = $matches->insert([
    'league_id' => $lid,
    'league_phase_id' => $lpid,
    'pair_id' => $pairId,
    'venue_id' => $vid,
    'home_participant_id' => $pHome,
    'away_participant_id' => $pAway,
    'start_time' => '2025-11-15 13:30:00'
  ]);

  echo "Match created: $mid\n";
  echo "All good \n";
} catch (Throwable $e) {
  echo "Smoke test failed: " . $e->getMessage() . "\n";
  exit(1);
}
