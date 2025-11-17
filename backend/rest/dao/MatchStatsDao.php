<?php
require_once __DIR__ . '/BaseDao.php';

class MatchStatsDao extends BaseDao {
  public function __construct() { parent::__construct("match_stats"); }

  public function listByMatch(int $match_id): array {
    $stmt = $this->connection->prepare(
      "SELECT ms.*, mp.name AS phase_name, p.name AS participant_name
       FROM match_stats ms
       JOIN match_phase mp ON mp.id = ms.match_phase_id
       JOIN participants p ON p.id = ms.participant_id
       WHERE ms.match_id = :m
       ORDER BY ms.match_phase_id, ms.id"
    );
    $stmt->execute([':m' => $match_id]);
    return $stmt->fetchAll();
  }
}
