<?php
require_once __DIR__ . '/BaseDao.php';

class ParticipantsDao extends BaseDao {
  public function __construct() { parent::__construct("participants"); }

  public function listByPhase(int $league_phase_id): array {
    $stmt = $this->connection->prepare(
      "SELECT * FROM participants WHERE league_phase_id=:p ORDER BY name"
    );
    $stmt->execute([':p' => $league_phase_id]);
    return $stmt->fetchAll();
  }

  public function insertParticipant(array $data): int {
    return $this->insert($data);
  }
  public function updateParticipant(int $id, array $data): bool {
    return $this->update($id, $data);
  }
  public function deleteParticipant(int $id): bool {
    return $this->delete($id);
  }


}
