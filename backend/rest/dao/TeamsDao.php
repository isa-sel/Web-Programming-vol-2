<?php
require_once __DIR__ . '/BaseDao.php';

class TeamsDao extends BaseDao {
  public function __construct() { parent::__construct("teams"); }

  public function listWithLookups(): array {
    $sql = "SELECT t.*, ac.name AS age_category, g.name AS gender
            FROM teams t
            JOIN age_category ac ON ac.id = t.age_category_id
            JOIN gender g ON g.id = t.gender_id
            ORDER BY t.name";
    return $this->connection->query($sql)->fetchAll();
  }


}
