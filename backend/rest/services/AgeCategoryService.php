<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/AgeCategoryDao.php';

class AgeCategoryService extends BaseService
{
    public function __construct() {
        $dao = new AgeCategoryDao();
        parent::__construct($dao);
    }

    public function insertAgeCategory(array $data) {
        return $this->dao->insert($data);
    }

    public function updateAgeCategory(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deleteAgeCategory(int $id) {
        return $this->dao->delete($id);
    }
}
