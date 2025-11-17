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


}
