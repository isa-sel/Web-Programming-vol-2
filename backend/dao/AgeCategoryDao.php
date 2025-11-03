<?php
require_once __DIR__ . '/BaseDao.php';

class AgeCategoryDao extends BaseDao {
  public function __construct() { parent::__construct("age_category"); }

  public function insertAgeCategory(array $data): int {
  return $this->insert($data);
  }
  public function updateAgeCategory(int $id, array $data): bool {
    return $this->update($id, $data);
  }
  public function deleteAgeCategory(int $id): bool {
    return $this->delete($id);
  }


}
