<?php
require_once __DIR__ . '/BaseDao.php';

class LeagueDao extends BaseDao {
  public function __construct() { parent::__construct("league"); }

  public function listWithLookups(): array {
    $sql = "SELECT l.*, ac.name AS age_category, g.name AS gender
            FROM league l
            JOIN age_category ac ON ac.id = l.age_category_id
            JOIN gender g ON g.id = l.gender_id
            ORDER BY l.from_date DESC, l.name";
    return $this->connection->query($sql)->fetchAll();
  }

   public function insertLeague(array $data): int {
  return $this->insert($data);
  }
  public function updateLeague(int $id, array $data): bool {
    return $this->update($id, $data);
  }
  public function deleteLeague(int $id): bool {
    return $this->delete($id);
  }


}
