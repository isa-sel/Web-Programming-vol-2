<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/LeaguePhaseDao.php';

class LeaguePhaseService extends BaseService
{
    public function __construct() {
        $dao = new LeaguePhaseDao();
        parent::__construct($dao);
    }

    public function listByLeague(int $league_id): array {
        return $this->dao->listByLeague($league_id);
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
