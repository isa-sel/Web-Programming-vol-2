<?php
require_once __DIR__ . '/BaseDao.php';

class VenuesDao extends BaseDao {
  public function __construct() { parent::__construct("venues"); }

  public function insertVenue(array $data): int {
    return $this->insert($data);
  }

  public function updateVenue(int $id, array $data): bool {
    return $this->update($id, $data);
  }

  public function deleteVenue(int $id): bool {
    return $this->delete($id);
  }

}
