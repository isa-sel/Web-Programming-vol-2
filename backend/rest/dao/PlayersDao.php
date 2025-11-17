<?php
require_once __DIR__ . '/BaseDao.php';

class PlayersDao extends BaseDao {
  public function __construct() { parent::__construct("players"); }

  public function listByGender(int $gender_id): array {
    $stmt = $this->connection->prepare("SELECT * FROM players WHERE gender_id=:g ORDER BY name");
    $stmt->execute([':g' => $gender_id]);
    return $stmt->fetchAll();
  }
  

}


/* Dodati:
 - list by age (u18, prvi tim itd)
 - list by club
*/ 