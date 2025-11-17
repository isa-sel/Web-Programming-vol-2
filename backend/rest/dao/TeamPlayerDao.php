<?php
require_once __DIR__ . '/BaseDao.php';

class TeamPlayerDao extends BaseDao {
  public function __construct() { parent::__construct("team_player"); }

  public function currentRoster(int $team_id, string $on_date): array {
    $stmt = $this->connection->prepare(
      "SELECT tp.*, p.name, p.birthday
       FROM team_player tp
       JOIN players p ON p.id = tp.player_id
       WHERE tp.team_id=:t
         AND tp.from_date <= :d
         AND (tp.until_date IS NULL OR tp.until_date > :d)
       ORDER BY p.name"
    );
    $stmt->execute([':t' => $team_id, ':d' => $on_date]);
    return $stmt->fetchAll();
  }


}
