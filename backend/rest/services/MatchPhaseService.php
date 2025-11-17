<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/MatchPhaseDao.php';

class MatchPhaseService extends BaseService {
    public function __construct() {
        $dao = new MatchPhaseDao();
        parent::__construct($dao);
    }

    public function insertPhase(array $data) {
        return $this->dao->insert($data);
    }

    public function updatePhase(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deletePhase(int $id) {
        return $this->dao->delete($id);
    }
}
