<?php
require_once __DIR__ . '/BaseDao.php';

class LeaguePhaseDao extends BaseDao {
  public function __construct() { parent::__construct("league_phase"); }

  public function listByLeague(int $league_id): array {
    $stmt = $this->connection->prepare("SELECT * FROM league_phase WHERE league_id=:id ORDER BY id");
    $stmt->execute([':id' => $league_id]);
    return $stmt->fetchAll();
  }
}
