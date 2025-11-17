<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/LeagueDao.php';

class LeagueService extends BaseService {

    public function __construct() {
        parent::__construct(new LeagueDao());
    }

    public function listWithLookups() {
        return $this->dao->listWithLookups();
    }

    public function insertLeague(array $data) {
        return $this->dao->insert($data);
    }

    public function updateLeague(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deleteLeague(int $id) {
        return $this->dao->delete($id);
    }
}
