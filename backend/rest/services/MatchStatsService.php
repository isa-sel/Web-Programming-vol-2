<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/MatchStatsDao.php';

class MatchStatsService extends BaseService
{
    public function __construct() {
        $dao = new MatchStatsDao();
        parent::__construct($dao);
    }

    public function insertMatchStats(array $data) {
        return $this->dao->insert($data);
    }

    public function updateMatchStats(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deleteMatchStats(int $id) {
        return $this->dao->delete($id);
    }
}
