<?php
require_once __DIR__ . '/BaseDao.php';

class MatchesDao extends BaseDao {
  public function __construct() { parent::__construct("matches"); }

  public function listByLeague(int $league_id): array {
    $stmt = $this->connection->prepare(
      "SELECT m.*, v.name AS venue,
              hp.name AS home_name, ap.name AS away_name
       FROM matches m
       LEFT JOIN venues v ON v.id = m.venue_id
       JOIN participants hp ON hp.id = m.home_participant_id
       JOIN participants ap ON ap.id = m.away_participant_id
       WHERE m.league_id=:l
       ORDER BY m.start_time"
    );
    $stmt->execute([':l' => $league_id]);
    return $stmt->fetchAll();
  }

  public function insertMatch(array $data): int {
    return $this->insert($data);
  }
  public function updateMatch(int $id, array $data): bool {
    return $this->update($id, $data);
  }
  public function deleteMatch(int $id): bool {
    return $this->delete($id);
  }


}
