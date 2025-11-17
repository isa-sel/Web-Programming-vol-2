<?php
require_once __DIR__ . '/BaseDao.php';

class PairsDao extends BaseDao {
  public function __construct() { parent::__construct("pairs"); }

  public function findByParticipants(int $a, int $b): ?array {
    $stmt = $this->connection->prepare(
      "SELECT * FROM pairs WHERE (participant_A_id=:a AND participant_B_id=:b)
                               OR (participant_A_id=:b AND participant_B_id=:a) LIMIT 1"
    );
    $stmt->execute([':a' => $a, ':b' => $b]);
    $row = $stmt->fetch();
    return $row ?: null;
  }
}
