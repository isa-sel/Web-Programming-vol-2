<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PairsDao.php';

class PairsService extends BaseService
{
    public function __construct() {
        $dao = new PairsDao();
        parent::__construct($dao);
    }

    public function insertPair(array $data) {
        return $this->dao->insert($data);
    }

    public function updatePair(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deletePair(int $id) {
        return $this->dao->delete($id);
    }
}
